const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management - DOM Product Admin Panel
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// Admin Panel Assets (AdminLTE + Custom)
mix.js('resources/js/admin.js', 'public/js')
    .css('resources/css/admin.css', 'public/css')
    .options({
        processCssUrls: false // AdminLTE CDN orqali yuklanadi
    });

// Web Frontend Assets
mix.js('resources/js/web.js', 'public/js')
    .css('resources/css/web.css', 'public/css')
    .options({
        processCssUrls: false // Tailwind CDN orqali yuklanadi
    });

// Version for cache busting
mix.version();
