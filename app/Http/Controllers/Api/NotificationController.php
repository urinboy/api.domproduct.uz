<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth:sanctum');
        $this->notificationService = $notificationService;
    }

    /**
     * Foydalanuvchi bildirishnomalarini olish
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $notifications = $this->notificationService->getUserNotifications($user);

            return response()->json([
                'success' => true,
                'message' => 'Bildirishnomalar muvaffaqiyatli olindi',
                'data' => [
                    'notifications' => $notifications->items(),
                    'pagination' => [
                        'current_page' => $notifications->currentPage(),
                        'total_pages' => $notifications->lastPage(),
                        'total_items' => $notifications->total(),
                        'per_page' => $notifications->perPage(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bildirishnomalarni olishda xatolik',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * O'qilmagan bildirishnomalar sonini olish
     */
    public function unreadCount(Request $request)
    {
        try {
            $user = $request->user();
            $count = $this->notificationService->getUnreadCount($user);

            return response()->json([
                'success' => true,
                'data' => [
                    'unread_count' => $count
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'O\'qilmagan bildirishnomalar sonini olishda xatolik',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Bildirishnomani o'qilgan deb belgilash
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            $user = $request->user();

            $notification = Notification::where('id', $id)
                                      ->where('user_id', $user->id)
                                      ->firstOrFail();

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Bildirishnoma o\'qilgan deb belgilandi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bildirishnomani belgilashda xatolik',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Barcha bildirishnomalarni o'qilgan deb belgilash
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $user = $request->user();

            Notification::where('user_id', $user->id)
                       ->where('channel', 'database')
                       ->unread()
                       ->update([
                           'status' => 'read',
                           'read_at' => now()
                       ]);

            return response()->json([
                'success' => true,
                'message' => 'Barcha bildirishnomalar o\'qilgan deb belgilandi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bildirishnomalarni belgilashda xatolik',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Bildirishnomani o'chirish
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();

            $notification = Notification::where('id', $id)
                                      ->where('user_id', $user->id)
                                      ->firstOrFail();

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bildirishnoma o\'chirildi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bildirishnomani o\'chirishda xatolik',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Test bildirishnoma yuborish (faqat test uchun)
     */
    public function sendTest(Request $request)
    {
        try {
            $user = $request->user();

            $this->notificationService->sendDatabase(
                $user,
                'Test bildirishnoma',
                'Bu test bildirishnomasi. Tizim to\'g\'ri ishlayapti!'
            );

            return response()->json([
                'success' => true,
                'message' => 'Test bildirishnoma yuborildi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test bildirishnomani yuborishda xatolik',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
