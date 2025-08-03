<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  $permission
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('admin.login')
                ->with('error', 'Iltimos, avval tizimga kiring.');
        }

        $user = $request->user();

        // Check if user has admin access
        if (!in_array($user->role, ['admin', 'manager', 'employee'])) {
            abort(403, 'Sizda admin panelga kirish huquqi yo\'q.');
        }

        // Set language based on user preference or session
        $language = $this->determineLanguage($request, $user);
        App::setLocale($language);

        // Check specific permissions if provided
        if ($permission) {
            if (!$this->hasPermission($user, $permission)) {
                abort(403, 'Bu amalni bajarish uchun sizda huquq yo\'q.');
            }
        }

        return $next($request);
    }

    /**
     * Determine the language to use
     */
    private function determineLanguage(Request $request, $user)
    {
        // 1. Check if language is being switched via request
        if ($request->has('lang')) {
            $lang = $request->get('lang');
            if (in_array($lang, ['uz', 'en', 'ru'])) {
                Session::put('admin_language', $lang);
                return $lang;
            }
        }

        // 2. Check session
        if (Session::has('admin_language')) {
            return Session::get('admin_language');
        }

        // 3. Check user's preferred language
        if ($user->preferredLanguage) {
            return $user->preferredLanguage->code;
        }

        // 4. Default to Uzbek
        return 'uz';
    }

    /**
     * Check if user has specific permission
     */
    private function hasPermission($user, $permission)
    {
        $permissions = $this->getUserPermissions($user->role);
        
        return in_array($permission, $permissions) || in_array('*', $permissions);
    }

    /**
     * Get permissions based on user role
     */
    private function getUserPermissions($role)
    {
        $rolePermissions = [
            'admin' => ['*'], // Full access
            'manager' => [
                'dashboard.view',
                'users.view', 'users.create', 'users.edit',
                'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
                'products.view', 'products.create', 'products.edit', 'products.delete',
                'orders.view', 'orders.edit', 'orders.update_status',
                'reports.view',
                'languages.view', 'languages.edit',
                'translations.view', 'translations.edit'
            ],
            'employee' => [
                'dashboard.view',
                'categories.view',
                'products.view', 'products.create', 'products.edit',
                'orders.view', 'orders.edit'
            ],
        ];

        return $rolePermissions[$role] ?? [];
    }
}
