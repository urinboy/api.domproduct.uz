<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's profile page
     */
    public function index()
    {
        $user = Auth::user();
        return view('web.profile.index', compact('user'));
    }

    /**
     * Update the user's profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'birth_date', 'gender']);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('web.profile')
                        ->with('success', 'Профиль успешно обновлен!');
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Текущий пароль неверен.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('web.profile')
                        ->with('success', 'Пароль успешно изменен!');
    }

    /**
     * Get user orders
     */
    public function orders()
    {
        $user = Auth::user();
        // $orders = $user->orders()->with(['items.product'])->orderBy('created_at', 'desc')->get();

        return view('web.profile.orders');
    }

    /**
     * Get user wishlist
     */
    public function wishlist()
    {
        $user = Auth::user();
        $wishlistItems = $user->getFavoritesWithProducts();

        return view('web.profile.wishlist', compact('wishlistItems'));
    }

    /**
     * Get user addresses
     */
    public function addresses()
    {
        $user = Auth::user();
        // $addresses = $user->addresses;

        return view('web.profile.addresses');
    }
}

