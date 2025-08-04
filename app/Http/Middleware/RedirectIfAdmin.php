<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdmin
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
                // Agar admin bo'lsa, admin dashboardga yo'naltiramiz
        if (Auth::check() && $this->isAdmin(Auth::user())) {
            return redirect('/admin');
        }

        return $next($request);
    }

    /**
     * Check if user is admin
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    private function isUserAdmin($user)
    {
        // Admin email whitelist
        $adminEmails = [
            'admin@domproduct.uz',
            'urinboydev@gmail.com',
            'administrator@domproduct.uz'
        ];

        // Check by email
        if (in_array($user->email, $adminEmails)) {
            return true;
        }

        // Check by phone
        $adminPhones = [
            '+998901234567',
            '+998991234567'
        ];

        if (in_array($user->phone, $adminPhones)) {
            return true;
        }

        // Check if user has admin role (if role system is implemented)
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        return false;
    }
}
