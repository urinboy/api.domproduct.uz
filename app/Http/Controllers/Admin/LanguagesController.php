<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LanguagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Language::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Default filter
        if ($request->has('is_default') && $request->is_default !== '') {
            $query->where('is_default', $request->is_default);
        }

        $languages = $query->ordered()->paginate($request->per_page ?? 15);

        return view('admin.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:5|unique:languages,code',
            'flag' => 'nullable|string|max:50',
            'sort_order' => 'integer|min:0'
        ]);

        // Handle checkboxes manually
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['is_default'] = $request->has('is_default') ? 1 : 0;

        try {
            DB::beginTransaction();

            // If this is set as default, unset other defaults and make it active
            if ($validated['is_default']) {
                Language::where('is_default', true)->update(['is_default' => false]);
                $validated['is_active'] = 1; // Default language must be active
            }

            $language = Language::create($validated);

            DB::commit();

            return redirect()->route('admin.languages.index')
                ->with('success', __('admin.language_created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('admin.error_occurred') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Language $language)
    {
        $language->load(['translations']);

        // Get translation statistics if needed
        $translationsStats = [];

        // You can add more models here that have translations
        $models = [
            'Category' => \App\Models\Category::class,
            'Product' => \App\Models\Product::class,
            // Add more models as needed
        ];

        foreach ($models as $name => $model) {
            if (class_exists($model) && method_exists($model, 'translations')) {
                try {
                    $count = $model::whereHas('translations', function ($query) use ($language) {
                        $query->where('language_code', $language->code);
                    })->count();

                    $lastUpdatedModel = $model::whereHas('translations', function ($query) use ($language) {
                        $query->where('language_code', $language->code);
                    })->latest('updated_at')->first();

                    $lastUpdated = $lastUpdatedModel ? $lastUpdatedModel->updated_at : null;

                    $translationsStats[] = [
                        'model' => $name,
                        'count' => $count,
                        'last_updated' => $lastUpdated
                    ];
                } catch (\Exception $e) {
                    // Skip if model doesn't exist or has issues
                    continue;
                }
            }
        }

        return view('admin.languages.show', compact('language', 'translationsStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:5|unique:languages,code,' . $language->id,
            'flag' => 'nullable|string|max:50',
            'sort_order' => 'integer|min:0'
        ]);

        // Handle checkboxes manually
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['is_default'] = $request->has('is_default') ? 1 : 0;

        try {
            DB::beginTransaction();

            // If this is set as default, unset other defaults
            if ($validated['is_default']) {
                Language::where('id', '!=', $language->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);

                // Default language must be active
                $validated['is_active'] = 1;
            }

            // Don't allow deactivating default language
            if ($language->is_default && !$validated['is_active']) {
                return back()->withInput()
                    ->with('error', __('admin.cannot_deactivate_default_language'));
            }

            $language->update($validated);

            DB::commit();

            return redirect()->route('admin.languages.index')
                ->with('success', __('admin.language_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('admin.error_occurred') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Language $language)
    {
        try {
            // Check if language has translations
            if ($language->translations()->exists()) {
                return back()->with('error', __('admin.language_has_translations_cannot_delete'));
            }

            // Check if it's the default language
            if ($language->is_default) {
                return back()->with('error', __('admin.cannot_delete_default_language'));
            }

            $language->delete();

            return redirect()->route('admin.languages.index')
                ->with('success', __('admin.language_deleted_successfully'));

        } catch (\Exception $e) {
            return back()->with('error', __('admin.error_occurred') . ': ' . $e->getMessage());
        }
    }

    /**
     * Toggle language status
     */
    public function toggleStatus(Language $language)
    {
        try {
            // Don't allow deactivating default language
            if ($language->is_default && $language->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.cannot_deactivate_default_language')
                ]);
            }

            $language->update(['is_active' => !$language->is_active]);

            return response()->json([
                'success' => true,
                'message' => __('admin.language_status_updated', [
                    'status' => $language->is_active ? __('admin.active') : __('admin.inactive')
                ])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.error_occurred') . ': ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Set as default language
     */
    public function setDefault(Language $language)
    {
        try {
            DB::beginTransaction();

            // Unset current default
            Language::where('is_default', true)->update(['is_default' => false]);

            // Set new default and activate it
            $language->update([
                'is_default' => true,
                'is_active' => true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('admin.default_language_set', ['language' => $language->name])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('admin.error_occurred') . ': ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:languages,id',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        try {
            $languages = Language::whereIn('id', $request->ids)->get();
            $action = $request->action;
            $count = 0;

            foreach ($languages as $language) {
                switch ($action) {
                    case 'activate':
                        if (!$language->is_active) {
                            $language->update(['is_active' => true]);
                            $count++;
                        }
                        break;

                    case 'deactivate':
                        // Don't deactivate default language
                        if ($language->is_active && !$language->is_default) {
                            $language->update(['is_active' => false]);
                            $count++;
                        }
                        break;

                    case 'delete':
                        // Don't delete default language or languages with translations
                        if (!$language->is_default && !$language->translations()->exists()) {
                            $language->delete();
                            $count++;
                        }
                        break;
                }
            }

            $message = '';
            switch ($action) {
                case 'activate':
                    $message = __('admin.languages_activated', ['count' => $count]);
                    break;
                case 'deactivate':
                    $message = __('admin.languages_deactivated', ['count' => $count]);
                    break;
                case 'delete':
                    $message = __('admin.languages_deleted', ['count' => $count]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.error_occurred') . ': ' . $e->getMessage()
            ]);
        }
    }
}
