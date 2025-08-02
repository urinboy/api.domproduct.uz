<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users with filtering and pagination
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'nullable|string|in:admin,manager,employee,customer',
            'is_active' => 'nullable|boolean',
            'city' => 'nullable|string|max:100',
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100',
            'sort_by' => 'nullable|string|in:name,email,role,created_at,last_login_at',
            'sort_order' => 'nullable|string|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = User::query();

            // Filtering
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }

            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            if ($request->has('city')) {
                $query->where('city', 'like', '%' . $request->city . '%');
            }

            // Search in name, email, phone
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%')
                      ->orWhere('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%');
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $users = $query->with('preferredLanguage')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => new UserCollection($users)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        try {
            $user = User::with('preferredLanguage')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'User retrieved successfully',
                'data' => new UserResource($user)
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error_code' => 'USER_NOT_FOUND'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $id,
            'role' => 'sometimes|required|string|in:admin,manager,employee,customer',
            'password' => 'nullable|string|min:6',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'nullable|boolean',
            'email_verified' => 'nullable|boolean',
            'phone_verified' => 'nullable|boolean',
            'preferred_language' => 'nullable|string|in:uz,en,ru',
            'avatar' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($id);

            // Prevent admin from changing their own role to non-admin
            if ($request->user()->id === $user->id &&
                $request->has('role') &&
                $request->role !== 'admin' &&
                $user->role === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot change your own admin role',
                    'error_code' => 'CANNOT_CHANGE_OWN_ADMIN_ROLE'
                ], 403);
            }

            $updateData = $request->only([
                'name', 'first_name', 'last_name', 'email', 'phone', 'role',
                'birth_date', 'gender', 'address', 'city', 'district', 'postal_code',
                'latitude', 'longitude', 'is_active', 'email_verified', 'phone_verified', 'avatar'
            ]);

            // Handle password update
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Handle preferred language
            if ($request->has('preferred_language')) {
                $language = Language::where('code', $request->preferred_language)->first();
                if ($language) {
                    $updateData['preferred_language_id'] = $language->id;
                }
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => new UserResource($user->fresh())
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error_code' => 'USER_NOT_FOUND'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent admin from deleting themselves
            if ($request->user()->id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete your own account',
                    'error_code' => 'CANNOT_DELETE_OWN_ACCOUNT'
                ], 403);
            }

            // Prevent deletion of the last admin
            if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the last admin user',
                    'error_code' => 'CANNOT_DELETE_LAST_ADMIN'
                ], 403);
            }

            // Revoke all user tokens
            $user->tokens()->delete();

            // Soft delete (deactivate) instead of hard delete
            $user->update(['is_active' => false]);
            // $user->delete(); // Uncomment for hard delete

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error_code' => 'USER_NOT_FOUND'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user statistics
     */
    public function statistics(Request $request)
    {
        try {
            $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
            $dateTo = $request->date_to ?? now()->format('Y-m-d');

            // Asosiy statistika
            $totalUsers = User::count();
            $activeUsers = User::where('is_active', true)->count();
            $inactiveUsers = User::where('is_active', false)->count();
            $verifiedUsers = User::where('email_verified', true)->where('phone_verified', true)->count();

            // Rol bo'yicha statistika
            $roleStats = User::selectRaw('role, COUNT(*) as count')
                            ->groupBy('ro‌le')
                            ->get()
                            ->mapWithKeys(function ($item) {
                                return [$item->role => [
                                    'count' => $item->count,
                                    'name' => $this->getRoleName($item->role)
                                ]];
                            });

            // Yangi ro'yxatdan o'tganlar
            $recentRegistrations = User::whereDate('created_at', '>=', $dateFrom)
                                      ->whereDate('created_at', '<=', $dateTo)
                                      ->count();

            // Oxirgi kirganlar
            $recentLogins = User::whereDate('last_login_at', '>=', now()->subDays(7))
                               ->count();

            // Kunlik ro'yxatdan o'tish statistikasi
            $dailyRegistrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                     ->whereDate('created_at', '>=', $dateFrom)
                                     ->whereDate('created_at', '<=', $dateTo)
                                     ->groupBy('date')
                                     ->orderBy('date', 'desc')
                                     ->get();

            // Shahar bo'yicha statistika
            $cityStats = User::selectRaw('city, COUNT(*) as count')
                            ->whereNotNull('city')
                            ->groupBy('city')
                            ->orderBy('count', 'desc')
                            ->limit(10)
                            ->get();

            $stats = [
                'period' => [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                ],
                'summary' => [
                    'total_users' => $totalUsers,
                    'active_users' => $activeUsers,
                    'inactive_users' => $inactiveUsers,
                    'verified_users' => $verifiedUsers,
                    'verification_rate' => $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 2) : 0
                ],
                'role_breakdown' => $roleStats,
                'activity' => [
                    'recent_registrations' => $recentRegistrations,
                    'recent_logins' => $recentLogins,
                    'login_rate' => $totalUsers > 0 ? round(($recentLogins / $totalUsers) * 100, 2) : 0
                ],
                'daily_registrations' => $dailyRegistrations,
                'top_cities' => $cityStats
            ];

            return response()->json([
                'success' => true,
                'message' => 'Foydalanuvchi statistikasi muvaffaqiyatli olindi',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rol nomini olish
     */
    private function getRoleName($role)
    {
        $roles = [
            'admin' => 'Administrator',
            'manager' => 'Menejer',
            'employee' => 'Xodim',
            'customer' => 'Mijoz',
        ];

        return $roles[$role] ?? $role;
    }
}
