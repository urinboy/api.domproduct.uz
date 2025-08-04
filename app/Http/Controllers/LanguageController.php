<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    /**
     * Available languages
     */
    private $availableLocales = ['uz', 'en', 'ru'];

    /**
     * Switch language
     *
     * @param Request $request
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLanguage(Request $request, $locale)
    {
        // Validate locale
        if (!in_array($locale, $this->availableLocales)) {
            abort(404);
        }

        // Set locale in session
        Session::put('locale', $locale);
        App::setLocale($locale);

        // Get previous URL or redirect to home
        $previousUrl = url()->previous();

        // If the previous URL has a lang parameter, replace it
        $parsedUrl = parse_url($previousUrl);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            $queryParams['lang'] = $locale;
            $newQuery = http_build_query($queryParams);
            $redirectUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] .
                          (isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '') .
                          $parsedUrl['path'] . '?' . $newQuery;
        } else {
            $redirectUrl = $previousUrl . '?lang=' . $locale;
        }

        return redirect($redirectUrl);
    }

    /**
     * Get current language
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentLanguage()
    {
        return response()->json([
            'current_locale' => App::getLocale(),
            'available_locales' => $this->availableLocales,
            'locale_names' => [
                'uz' => 'O\'zbekcha',
                'en' => 'English',
                'ru' => 'Русский'
            ]
        ]);
    }

    /**
     * Get translations for a specific key
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTranslations(Request $request)
    {
        $key = $request->input('key');
        $locale = $request->input('locale', App::getLocale());

        if (!$key) {
            return response()->json(['error' => 'Translation key is required'], 400);
        }

        if (!in_array($locale, $this->availableLocales)) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }

        $translation = __($key, [], $locale);

        return response()->json([
            'key' => $key,
            'locale' => $locale,
            'translation' => $translation
        ]);
    }
}
