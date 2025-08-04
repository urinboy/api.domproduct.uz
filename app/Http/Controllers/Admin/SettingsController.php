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
}
