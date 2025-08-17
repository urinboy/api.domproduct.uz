<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class TranslationController extends Controller
{
    /**
     * Display a listing of translations
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $languageId = $request->get('language_id');
            $group = $request->get('group');

            $query = Translation::with(['language']);

            // Search functionality
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('key', 'LIKE', "%{$search}%")
                      ->orWhere('value', 'LIKE', "%{$search}%");
                });
            }

            // Filter by language
            if ($languageId) {
                $query->where('language_id', $languageId);
            }

            // Filter by group
            if ($group) {
                $query->where('key', 'LIKE', "{$group}.%");
            }

            $translations = $query->orderBy('key')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $translations,
                'message' => 'Translations retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving translations',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Store a new translation
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'language_id' => 'required|exists:languages,id',
                'key' => 'required|string|max:255',
                'value' => 'required|string',
                'group' => 'nullable|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if translation already exists
            $existing = Translation::where('language_id', $request->language_id)
                                 ->where('key', $request->key)
                                 ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation already exists for this language and key'
                ], 409);
            }

            $translation = Translation::create($request->validated());
            $translation->load('language');

            // Clear cache
            $this->clearTranslationCache();

            return response()->json([
                'success' => true,
                'data' => $translation,
                'message' => 'Translation created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating translation',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Display the specified translation
     */
    public function show(string $id): JsonResponse
    {
        try {
            $translation = Translation::with('language')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $translation,
                'message' => 'Translation retrieved successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Translation not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving translation',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Update the specified translation
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $translation = Translation::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'language_id' => 'sometimes|required|exists:languages,id',
                'key' => 'sometimes|required|string|max:255',
                'value' => 'sometimes|required|string',
                'group' => 'nullable|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check for duplicates if key or language_id is being updated
            if ($request->has('key') || $request->has('language_id')) {
                $languageId = $request->get('language_id', $translation->language_id);
                $key = $request->get('key', $translation->key);

                $existing = Translation::where('language_id', $languageId)
                                     ->where('key', $key)
                                     ->where('id', '!=', $id)
                                     ->first();

                if ($existing) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Translation already exists for this language and key'
                    ], 409);
                }
            }

            $translation->update($request->only(['language_id', 'key', 'value', 'group']));
            $translation->load('language');

            // Clear cache
            $this->clearTranslationCache();

            return response()->json([
                'success' => true,
                'data' => $translation,
                'message' => 'Translation updated successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Translation not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating translation',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Remove the specified translation
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $translation = Translation::findOrFail($id);
            $translation->delete();

            // Clear cache
            $this->clearTranslationCache();

            return response()->json([
                'success' => true,
                'message' => 'Translation deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Translation not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting translation',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Get translations by language code
     */
    public function getByLanguage(string $languageCode): JsonResponse
    {
        try {
            $cacheKey = "translations.{$languageCode}";

            $translations = Cache::remember($cacheKey, 3600, function() use ($languageCode) {
                $language = Language::where('code', $languageCode)->first();

                if (!$language) {
                    return null;
                }

                return Translation::where('language_id', $language->id)
                    ->pluck('value', 'key')
                    ->toArray();
            });

            if (is_null($translations)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Language not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $translations,
                'message' => 'Translations retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving translations',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Get translations by language code and group
     */
    public function getByGroup(string $languageCode, string $group): JsonResponse
    {
        try {
            $cacheKey = "translations.{$languageCode}.{$group}";

            $translations = Cache::remember($cacheKey, 3600, function() use ($languageCode, $group) {
                $language = Language::where('code', $languageCode)->first();

                if (!$language) {
                    return null;
                }

                return Translation::where('language_id', $language->id)
                    ->where('key', 'LIKE', "{$group}.%")
                    ->pluck('value', 'key')
                    ->toArray();
            });

            if (is_null($translations)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Language not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $translations,
                'message' => 'Translations retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving translations',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Bulk upsert translations
     */
    public function bulkUpsert(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'language_id' => 'required|exists:languages,id',
                'translations' => 'required|array',
                'translations.*.key' => 'required|string|max:255',
                'translations.*.value' => 'required|string',
                'translations.*.group' => 'nullable|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $languageId = $request->language_id;
            $translations = $request->translations;
            $created = 0;
            $updated = 0;

            foreach ($translations as $translationData) {
                $translation = Translation::updateOrCreate(
                    [
                        'language_id' => $languageId,
                        'key' => $translationData['key']
                    ],
                    [
                        'value' => $translationData['value'],
                        'group' => $translationData['group'] ?? null
                    ]
                );

                if ($translation->wasRecentlyCreated) {
                    $created++;
                } else {
                    $updated++;
                }
            }

            // Clear cache
            $this->clearTranslationCache();

            return response()->json([
                'success' => true,
                'message' => "Bulk operation completed: {$created} created, {$updated} updated",
                'data' => [
                    'created' => $created,
                    'updated' => $updated,
                    'total' => count($translations)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk operation',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Clear translation cache
     */
    private function clearTranslationCache(): void
    {
        $languages = Language::all();

        foreach ($languages as $language) {
            Cache::forget("translations.{$language->code}");

            // Clear group caches (common groups)
            $groups = ['web', 'api', 'admin', 'common', 'validation', 'auth'];
            foreach ($groups as $group) {
                Cache::forget("translations.{$language->code}.{$group}");
            }
        }
    }
}
