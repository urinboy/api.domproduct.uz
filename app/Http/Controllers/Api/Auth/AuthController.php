<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'preferred_language' => 'nullable|string|in:uz,en,ru'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Preferred language ID'sini olish
        $languageId = null;
        if ($request->preferred_language) {
            $language = Language::where('code', $request->preferred_language)->first();
            $languageId = $language ? $language->id : 1; // default uz
        } else {
            $languageId = 1; // default uz
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'customer', // default role
                'city' => $request->city,
                'district' => $request->district,
                'address' => $request->address,
                'preferred_language_id' => $languageId,
                'is_active' => true,
                'email_verified' => false,
                'phone_verified' => false,
            ]);

            // API token yaratish
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'full_name' => $user->full_name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => $user->role,
                        'city' => $user->city,
                        'district' => $user->district,
                        'is_active' => $user->is_active,
                        'email_verified' => $user->email_verified,
                        'phone_verified' => $user->phone_verified,
                        'preferred_language' => $user->preferredLanguage->code ?? 'uz'
                    ],
                    'token' => $token
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'error_code' => 'INVALID_CREDENTIALS'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Account is deactivated',
                'error_code' => 'ACCOUNT_DEACTIVATED'
            ], 403);
        }

        // Last login vaqtini yangilash
        $user->update(['last_login_at' => now()]);

        // API token yaratish
        $deviceName = $request->device_name ?? 'api-token';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'city' => $user->city,
                    'district' => $user->district,
                    'address' => $user->address,
                    'is_active' => $user->is_active,
                    'email_verified' => $user->email_verified,
                    'phone_verified' => $user->phone_verified,
                    'preferred_language' => $user->preferredLanguage->code ?? 'uz',
                    'last_login_at' => $user->last_login_at
                ],
                'token' => $token,
                'permissions' => [
                    'can_manage_categories' => $user->canManageCategories(),
                    'can_manage_products' => $user->canManageProducts(),
                    'can_manage_users' => $user->canManageUsers(),
                    'can_view_reports' => $user->canViewReports()
                ]
            ]
        ], 200);
    }

    /**
     * Get current user info
     */
    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'message' => 'User data retrieved successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'city' => $user->city,
                    'district' => $user->district,
                    'address' => $user->address,
                    'birth_date' => $user->birth_date,
                    'gender' => $user->gender,
                    'is_active' => $user->is_active,
                    'email_verified' => $user->email_verified,
                    'phone_verified' => $user->phone_verified,
                    'preferred_language' => $user->preferredLanguage->code ?? 'uz',
                    'last_login_at' => $user->last_login_at,
                    'created_at' => $user->created_at
                ],
                'permissions' => [
                    'can_manage_categories' => $user->canManageCategories(),
                    'can_manage_products' => $user->canManageProducts(),
                    'can_manage_users' => $user->canManageUsers(),
                    'can_view_reports' => $user->canViewReports()
                ]
            ]
        ], 200);
    }

    /**
     * Logout user (revoke current token)
     */
    public function logout(Request $request)
    {
        // Joriy tokenni o'chirish
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ], 200);
    }

    /**
     * Logout from all devices (revoke all tokens)
     */
    public function logoutAll(Request $request)
    {
        // Barcha tokenlarni o'chirish
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices'
        ], 200);
    }
}
