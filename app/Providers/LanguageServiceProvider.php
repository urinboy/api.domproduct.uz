<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\LanguageHelper;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Share language data with all views
        View::composer('*', function ($view) {
            $view->with([
                'currentLanguage' => LanguageHelper::getCurrentLanguage(),
                'currentLanguageInfo' => LanguageHelper::getCurrentLanguageInfo(),
                'availableLanguages' => LanguageHelper::getAvailableLanguages(),
                'isRTL' => LanguageHelper::isRTL(),
            ]);
        });
    }
}
