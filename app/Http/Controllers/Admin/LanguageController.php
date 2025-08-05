<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Available languages
     */
    private $availableLocales = ['uz', 'en', 'ru'];

    /**
     * Switch language (GET method for direct links)
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLanguage($locale)
    {
        // Validate locale
        if (!in_array($locale, $this->availableLocales)) {
            abort(404);
        }

        // Store locale in session
        Session::put('locale', $locale);

        // Set application locale
        App::setLocale($locale);

        // Get the previous URL or default to dashboard
        $previousUrl = url()->previous();
        $dashboardUrl = route('admin.dashboard');

        // If previous URL is login page, go to dashboard
        if (str_contains($previousUrl, '/login')) {
            $redirectUrl = $dashboardUrl;
        } else {
            $redirectUrl = $previousUrl;
        }

        // Add lang parameter to ensure middleware picks it up
        $separator = str_contains($redirectUrl, '?') ? '&' : '?';
        $redirectUrl .= $separator . 'lang=' . $locale;

        return redirect($redirectUrl)->with('success', 'Til muvaffaqiyatli o\'zgartirildi');
    }

    /**
     * Switch language (POST method for AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function switch(Request $request)
    {
        $locale = $request->input('locale');

        // Validate locale
        if (!in_array($locale, $this->availableLocales)) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }

        // Store locale in session
        Session::put('locale', $locale);

        // Set application locale
        App::setLocale($locale);

        return response()->json([
            'success' => true,
            'locale' => $locale,
            'message' => 'Language switched successfully'
        ]);
    }

    /**
     * Get current language
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrent()
    {
        return response()->json([
            'locale' => App::getLocale(),
            'available' => $this->availableLocales
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.languages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Implementation for storing language settings
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.languages.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.languages.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Implementation for updating language settings
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Implementation for deleting language settings
    }
}
