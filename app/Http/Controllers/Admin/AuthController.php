<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Admin login sahifasini ko'rsatish
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Admin login jarayoni
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => __('admin.login_required'),
            'password.required' => __('admin.password_required'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput($request->only('login'));
        }

        $login = $request->input('login');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Email yoki telefon orqali foydalanuvchini topish
        $user = User::where(function($query) use ($login) {
            $query->where('email', $login)
                  ->orWhere('phone', $login);
        })->first();

        // Foydalanuvchi mavjudligini tekshirish
        if (!$user) {
            return redirect()->back()
                           ->withErrors(['login' => __('admin.invalid_credentials')])
                           ->withInput($request->only('login'));
        }

        // Parolni tekshirish
        if (!Auth::attempt(['email' => $user->email, 'password' => $password], $remember)) {
            return redirect()->back()
                           ->withErrors(['password' => __('admin.invalid_password')])
                           ->withInput($request->only('login'));
        }

        // Admin tekshirish
        $adminEmails = [
            'admin@domproduct.uz',
            'admin@test.com',
            'manager@test.com',
            'employee@test.com',
            'urinboydev@gmail.com'
        ];

        $adminPhones = [
            '+998901234567',
            '+998991234567'
        ];

        $isAdmin = in_array($user->email, $adminEmails) ||
                  in_array($user->phone, $adminPhones);

        $request->session()->regenerate();

        if ($isAdmin) {
            // Admin dashboardga yo'naltirish
            return redirect()->intended('/admin')
                             ->with('success', __('admin.login_success'));
        } else {
            // Oddiy foydalanuvchilarni home sahifasiga yo'naltirish
            return redirect()->intended('/home')
                             ->with('success', __('auth.login_success', ['name' => $user->name]));
        }
    }

    /**
     * Admin logout jarayoni
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Agar admin bo'lsa admin login'ga, aks holda home'ga yo'naltirish
        if ($user && $this->isAdmin($user)) {
            return redirect()->route('admin.login')
                             ->with('success', __('admin.logout_success'));
        } else {
            return redirect('/home')
                             ->with('success', __('auth.logout_success'));
        }
    }

    /**
     * Foydalanuvchi admin ekanligini tekshirish
     */
    private function isAdmin($user)
    {
        $adminEmails = [
            'admin@domproduct.uz',
            'admin@test.com',
            'manager@test.com',
            'employee@test.com',
            'urinboydev@gmail.com'
        ];

        $adminPhones = [
            '+998901234567',
            '+998991234567'
        ];

        return in_array($user->email, $adminEmails) ||
               in_array($user->phone, $adminPhones);
    }

    /**
     * Dashboard sahifasiga redirect
     */
    public function dashboard()
    {
        // Agar user admin bo'lmasa, login sahifasiga yo'naltirish
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();
        if (!in_array($user->email, ['admin@test.com', 'manager@test.com', 'employee@test.com'])) {
            return redirect()->route('admin.login');
        }

        return redirect()->route('admin.dashboard');
    }
}
