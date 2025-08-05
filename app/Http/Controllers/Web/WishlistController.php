<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('web.auth.login')->with('error', 'Sevimlilar ro\'yxatini ko\'rish uchun tizimga kiring.');
        }

        $wishlistItems = $user->getFavoritesWithProducts();

        return view('web.profile.wishlist', compact('wishlistItems'));
    }

    /**
     * Add product to wishlist
     */
    public function add(Product $product)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Tizimga kirish kerak'
            ], 401);
        }

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Mahsulot mavjud emas'
            ], 404);
        }

        $added = $user->addToFavorites($product->id);

        if ($added) {
            return response()->json([
                'success' => true,
                'message' => 'Mahsulot sevimlilar ro\'yxatiga qo\'shildi',
                'action' => 'added',
                'favorites_count' => $user->favorites()->count()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mahsulot allaqachon sevimlilar ro\'yxatida'
        ]);
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Product $product)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Tizimga kirish kerak'
            ], 401);
        }

        $removed = $user->removeFromFavorites($product->id);

        if ($removed) {
            return response()->json([
                'success' => true,
                'message' => 'Mahsulot sevimlilardan olib tashlandi',
                'action' => 'removed',
                'favorites_count' => $user->favorites()->count()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mahsulot sevimlilar ro\'yxatida topilmadi'
        ]);
    }

    /**
     * Toggle product in wishlist (add if not exists, remove if exists)
     */
    public function toggle(Product $product)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Tizimga kirish kerak'
            ], 401);
        }

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Mahsulot mavjud emas'
            ], 404);
        }

        if ($user->hasFavorite($product->id)) {
            $user->removeFromFavorites($product->id);
            $action = 'removed';
            $message = 'Mahsulot sevimlilardan olib tashlandi';
        } else {
            $user->addToFavorites($product->id);
            $action = 'added';
            $message = 'Mahsulot sevimlilar ro\'yxatiga qo\'shildi';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'action' => $action,
            'favorites_count' => $user->favorites()->count()
        ]);
    }

    /**
     * Get wishlist count
     */
    public function count()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['count' => 0]);
        }

        return response()->json([
            'count' => $user->favorites()->count()
        ]);
    }

    /**
     * Clear all wishlist items
     */
    public function clear()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Tizimga kirish kerak'
            ], 401);
        }

        $user->favorites()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sevimlilar ro\'yxati tozalandi'
        ]);
    }
}
