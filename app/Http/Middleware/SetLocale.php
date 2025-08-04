<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Available languages
        $availableLocales = ['uz', 'en', 'ru'];

        // Get locale from URL parameter, session, or default
        $locale = null;

        // 1. Check URL parameter
        if ($request->has('lang') && in_array($request->lang, $availableLocales)) {
            $locale = $request->lang;
            Session::put('locale', $locale);
        }
        // 2. Check session
        elseif (Session::has('locale') && in_array(Session::get('locale'), $availableLocales)) {
            $locale = Session::get('locale');
        }
        // 3. Check Accept-Language header
        elseif ($request->hasHeader('Accept-Language')) {
            $browserLang = substr($request->header('Accept-Language'), 0, 2);
            if (in_array($browserLang, $availableLocales)) {
                $locale = $browserLang;
                Session::put('locale', $locale);
            }
        }

        // Default to Uzbek if no locale found
        if (!$locale || !in_array($locale, $availableLocales)) {
            $locale = 'uz';
            Session::put('locale', $locale);
        }

        // Set the application locale
        App::setLocale($locale);

        return $next($request);
    }
}
