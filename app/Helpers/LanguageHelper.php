<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageHelper
{
    /**
     * Available languages
     */
    public static $availableLocales = [
        'uz' => [
            'name' => 'O\'zbekcha',
            'flag' => '🇺🇿',
            'code' => 'uz'
        ],
        'en' => [
            'name' => 'English',
            'flag' => '🇺🇸',
            'code' => 'en'
        ],
        'ru' => [
            'name' => 'Русский',
            'flag' => '🇷🇺',
            'code' => 'ru'
        ]
    ];

    /**
     * Get current language
     */
    public static function getCurrentLanguage()
    {
        return App::getLocale();
    }

    /**
     * Get current language info
     */
    public static function getCurrentLanguageInfo()
    {
        $currentLocale = self::getCurrentLanguage();
        return self::$availableLocales[$currentLocale] ?? self::$availableLocales['uz'];
    }

    /**
     * Get all available languages
     */
    public static function getAvailableLanguages()
    {
        return self::$availableLocales;
    }

    /**
     * Check if language is supported
     */
    public static function isLanguageSupported($locale)
    {
        return array_key_exists($locale, self::$availableLocales);
    }

    /**
     * Get language switch URL
     */
    public static function getLanguageSwitchUrl($locale)
    {
        if (!self::isLanguageSupported($locale)) {
            return null;
        }

        return route('language.switch', $locale);
    }

    /**
     * Get RTL languages
     */
    public static function isRTL($locale = null)
    {
        $locale = $locale ?: self::getCurrentLanguage();
        $rtlLanguages = ['ar', 'fa', 'he', 'ur'];
        return in_array($locale, $rtlLanguages);
    }

    /**
     * Get translation with fallback
     */
    public static function trans($key, $parameters = [], $locale = null)
    {
        $locale = $locale ?: self::getCurrentLanguage();

        // Try to get translation for current locale
        $translation = __($key, $parameters, $locale);

        // If translation equals key (not found), try fallback
        if ($translation === $key && $locale !== 'uz') {
            $translation = __($key, $parameters, 'uz');
        }

        // If still not found, try English
        if ($translation === $key && $locale !== 'en') {
            $translation = __($key, $parameters, 'en');
        }

        return $translation;
    }

    /**
     * Set language preference in session
     */
    public static function setLanguagePreference($locale)
    {
        if (self::isLanguageSupported($locale)) {
            Session::put('locale', $locale);
            App::setLocale($locale);
            return true;
        }
        return false;
    }

    /**
     * Get browser preferred language
     */
    public static function getBrowserLanguage($httpAcceptLanguage = null)
    {
        if (!$httpAcceptLanguage) {
            $httpAcceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        }

        $languages = [];
        $acceptableLanguages = explode(',', $httpAcceptLanguage);

        foreach ($acceptableLanguages as $lang) {
            $parts = explode(';', $lang);
            $locale = trim($parts[0]);
            $quality = isset($parts[1]) ? (float) substr(trim($parts[1]), 2) : 1.0;

            // Get main language code (first 2 chars)
            $mainLang = substr($locale, 0, 2);

            if (self::isLanguageSupported($mainLang)) {
                $languages[$mainLang] = $quality;
            }
        }

        // Sort by quality
        arsort($languages);

        // Return first supported language
        return array_key_first($languages) ?: 'uz';
    }
}
