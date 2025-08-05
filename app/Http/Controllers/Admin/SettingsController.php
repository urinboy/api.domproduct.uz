<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            'site_name' => config('app.name'),
            'site_description' => 'DOM Product API - Product delivery service',
            'contact_email' => 'info@domproduct.uz',
            'contact_phone' => '+998 90 123 45 67',
            'address' => 'Tashkent, Uzbekistan',
            'currency' => 'UZS',
            'language' => 'uz',
            'timezone' => 'Asia/Tashkent',
            'maintenance_mode' => false,
            'allow_registration' => true,
            'email_verification' => false,
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'currency' => 'required|string|in:UZS,USD,EUR',
            'language' => 'required|string|in:uz,ru,en',
            'timezone' => 'required|string',
            'maintenance_mode' => 'boolean',
            'allow_registration' => 'boolean',
            'email_verification' => 'boolean',
        ]);

        // In a real application, you would save these to a settings table or config files
        // For now, we'll just redirect with success message

        return redirect()->route('admin.settings.index')
            ->with('success', __('admin.settings_updated_successfully'));
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');

            return response()->json([
                'success' => true,
                'message' => __('admin.cache_cleared_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.cache_clear_failed')
            ], 500);
        }
    }

    /**
     * Optimize application.
     */
    public function optimize()
    {
        try {
            \Artisan::call('optimize');
            \Artisan::call('config:cache');
            \Artisan::call('view:cache');
            \Artisan::call('route:cache');

            return response()->json([
                'success' => true,
                'message' => __('admin.app_optimized_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.optimization_failed')
            ], 500);
        }
    }

    /**
     * Backup data.
     */
    public function backup()
    {
        // This is a placeholder for backup functionality
        // In a real application, you would implement actual backup logic

        return redirect()->route('admin.settings.index')
            ->with('info', __('admin.backup_feature_coming_soon'));
    }
}
