<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Foydalanuvchilar ro'yxatini ko'rsatish
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Qidirish
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Rol bo'yicha filtrlash
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Status bo'yicha filtrlash
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }

        // Sanaga qarab saralash
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['name', 'email', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Yangi foydalanuvchi yaratish formasini ko'rsatish
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Yangi foydalanuvchini saqlash
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:500',
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'birth_date', 'gender', 'address'
        ]);

        $data['password'] = Hash::make($request->password);
        $data['email_verified_at'] = now(); // Admin tomonidan yaratilgan foydalanuvchilar avtomatik tasdiqlangan

        // Avatar yuklash
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($data);

        // Rollarni biriktiris
        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.index')
                        ->with('success', __('admin.user_created_successfully'));
    }

    /**
     * Foydalanuvchi ma'lumotlarini ko'rsatish
     */
    public function show(User $user)
    {
        $user->load(['roles', 'orders', 'productViews', 'ratings']);

        // Foydalanuvchi statistikasi
        $stats = [
            'orders_count' => $user->orders()->count(),
            'total_spent' => $user->orders()->where('status', 'completed')->sum('total_amount'),
            'products_viewed' => $user->productViews()->count(),
            'ratings_given' => $user->ratings()->count(),
            'average_rating' => $user->ratings()->avg('rating'),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Foydalanuvchi tahrirlash formasini ko'rsatish
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Foydalanuvchi ma'lumotlarini yangilash
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:500',
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'birth_date', 'gender', 'address'
        ]);

        // Parol yangilash (agar berilgan bo'lsa)
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Avatar yangilash
        if ($request->hasFile('avatar')) {
            // Eski avatarni o'chirish
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        // Rollarni yangilash
        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.index')
                        ->with('success', __('admin.user_updated_successfully'));
    }

    /**
     * Foydalanuvchini o'chirish
     */
    public function destroy(User $user)
    {
        // Admin rolini tekshirish - o'zini o'chira olmasligi kerak
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                            ->with('error', __('admin.cannot_delete_yourself'));
        }

        // Agar foydalanuvchida buyurtmalar bo'lsa, o'chirish o'rniga nofaol qilish
        if ($user->orders()->exists()) {
            $user->update(['email_verified_at' => null]);
            return redirect()->route('admin.users.index')
                            ->with('warning', __('admin.user_deactivated_has_orders'));
        }

        // Avatarni o'chirish
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', __('admin.user_deleted_successfully'));
    }

    /**
     * Foydalanuvchini faollashtirish/nofaollashtirish
     */
    public function toggleStatus(User $user)
    {
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $message = __('admin.user_deactivated');
        } else {
            $user->update(['email_verified_at' => now()]);
            $message = __('admin.user_activated');
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Foydalanuvchi parolini tiklash
     */
    public function resetPassword(User $user)
    {
        $newPassword = \Str::random(12);
        $user->update(['password' => Hash::make($newPassword)]);

        // Bu yerda email yuborish logikasini qo'shish mumkin
        // Mail::to($user->email)->send(new PasswordResetNotification($newPassword));

        return redirect()->back()
                        ->with('success', __('admin.password_reset_success'))
                        ->with('new_password', $newPassword);
    }

    /**
     * Ko'p foydalanuvchilarni birdaniga boshqarish
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->users);
        $count = $users->count();

        switch ($request->action) {
            case 'activate':
                $users->update(['email_verified_at' => now()]);
                $message = __('admin.users_activated', ['count' => $count]);
                break;

            case 'deactivate':
                $users->update(['email_verified_at' => null]);
                $message = __('admin.users_deactivated', ['count' => $count]);
                break;

            case 'delete':
                // O'zini o'chirmasligi uchun tekshirish
                $users = $users->where('id', '!=', auth()->id());
                $count = $users->count();
                $users->delete();
                $message = __('admin.users_deleted', ['count' => $count]);
                break;
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }
}
