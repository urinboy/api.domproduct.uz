<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Email bildirishnoma yaratish va yuborish
     */
    public function sendEmail(
        $recipient,
        string $title,
        string $message,
        string $template = null,
        array $data = []
    ): Notification {
        // Qabul qiluvchini aniqlash
        $userId = null;
        $email = null;

        if ($recipient instanceof User) {
            $userId = $recipient->id;
            $email = $recipient->email;
        } elseif (is_string($recipient)) {
            $email = $recipient;
        } else {
            throw new \InvalidArgumentException('Noto\'g\'ri qabul qiluvchi turi');
        }

        // Bildirishnoma yaratish
        $notification = Notification::create([
            'type' => 'email',
            'channel' => 'email',
            'user_id' => $userId,
            'recipient_email' => $email,
            'title' => $title,
            'message' => $message,
            'template' => $template,
            'data' => $data,
            'status' => 'pending',
        ]);

        // Email yuborish
        try {
            $this->sendEmailNotification($notification);
        } catch (\Exception $e) {
            $notification->markAsFailed($e->getMessage());
            Log::error('Email yuborishda xatolik: ' . $e->getMessage());
        }

        return $notification;
    }

    /**
     * Database bildirishnoma yaratish
     */
    public function sendDatabase(
        User $user,
        string $title,
        string $message,
        array $data = []
    ): Notification {
        return Notification::create([
            'type' => 'database',
            'channel' => 'database',
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Buyurtma tasdiqlanish bildirshnomaasi
     */
    public function sendOrderConfirmation($order, User $user = null): void
    {
        $user = $user ?: $order->user;

        $title = 'Buyurtma tasdiqlandi';
        $message = "Sizning #{$order->order_number} raqamli buyurtmangiz tasdiqlandi.";

        $data = [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'total_amount' => $order->total_amount,
            'status' => $order->status,
        ];

        // Email yuborish
        $this->sendEmail(
            $user,
            $title,
            $message,
            'order_confirmation',
            $data
        );

        // Database ga saqlash
        $this->sendDatabase($user, $title, $message, $data);
    }

    /**
     * To'lov muvaffaqiyatli bildirshnomaasi
     */
    public function sendPaymentSuccess($payment): void
    {
        $order = $payment->order;
        $user = $order->user;

        $title = 'To\'lov muvaffaqiyatli';
        $message = "Sizning #{$order->order_number} buyurtmangiz uchun to'lov muvaffaqiyatli qabul qilindi.";

        $data = [
            'payment_id' => $payment->id,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
        ];

        $this->sendEmail(
            $user,
            $title,
            $message,
            'payment_success',
            $data
        );

        $this->sendDatabase($user, $title, $message, $data);
    }

    /**
     * To'lov muvaffaqiyatsiz bildirshnomaasi
     */
    public function sendPaymentFailed($payment): void
    {
        $order = $payment->order;
        $user = $order->user;

        $title = 'To\'lov muvaffaqiyatsiz';
        $message = "Sizning #{$order->order_number} buyurtmangiz uchun to'lov amalga oshmadi. Iltimos, qayta urinib ko'ring.";

        $data = [
            'payment_id' => $payment->id,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'amount' => $payment->amount,
            'error_message' => $payment->failure_reason,
        ];

        $this->sendEmail(
            $user,
            $title,
            $message,
            'payment_failed',
            $data
        );

        $this->sendDatabase($user, $title, $message, $data);
    }

    /**
     * Zaxira kamayishi haqida admin bildirshnomaasi
     */
    public function sendLowStockAlert($product): void
    {
        // Admin foydalanuvchilarni topish
        $admins = User::where('role', 'admin')->get();

        $title = 'Zaxira kamayib ketdi';
        $message = "'{$product->name}' mahsuloti zaxirasi kam qoldi. Joriy zaxira: {$product->stock_quantity}";

        $data = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'sku' => $product->sku,
            'current_stock' => $product->stock_quantity,
            'min_stock_level' => $product->min_stock_level,
        ];

        foreach ($admins as $admin) {
            $this->sendEmail(
                $admin,
                $title,
                $message,
                'low_stock_alert',
                $data
            );

            $this->sendDatabase($admin, $title, $message, $data);
        }
    }

    /**
     * Buyurtma holati o'zgarishi bildirshnomaasi
     */
    public function sendOrderStatusUpdate($order, string $oldStatus, string $newStatus): void
    {
        $user = $order->user;

        $statusLabels = [
            'pending' => 'Kutilmoqda',
            'confirmed' => 'Tasdiqlandi',
            'processing' => 'Tayyorlanmoqda',
            'shipped' => 'Yuborildi',
            'delivered' => 'Yetkazildi',
            'cancelled' => 'Bekor qilindi',
        ];

        $title = 'Buyurtma holati o\'zgardi';
        $message = "Sizning #{$order->order_number} buyurtmangiz holati '{$statusLabels[$newStatus]}' ga o'zgartirildi.";

        $data = [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'tracking_number' => $order->tracking_number,
        ];

        $this->sendEmail(
            $user,
            $title,
            $message,
            'order_status_update',
            $data
        );

        $this->sendDatabase($user, $title, $message, $data);
    }

    /**
     * Email yuborish jarayoni
     */
    private function sendEmailNotification(Notification $notification): void
    {
        try {
            // Bu yerda email template asosida email yuboriladi
            // Hozircha oddiy email yuboramiz

            Mail::raw($notification->message, function ($message) use ($notification) {
                $message->to($notification->recipient_email)
                       ->subject($notification->title);
            });

            $notification->markAsSent();

        } catch (\Exception $e) {
            $notification->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    /**
     * Kutilayotgan bildirishnomalarni yuborish
     */
    public function processPendingNotifications(): int
    {
        $notifications = Notification::pending()
                                   ->where('type', 'email')
                                   ->limit(100)
                                   ->get();

        $processedCount = 0;

        foreach ($notifications as $notification) {
            try {
                $this->sendEmailNotification($notification);
                $processedCount++;
            } catch (\Exception $e) {
                Log::error('Bildirishnoma yuborishda xatolik: ' . $e->getMessage(), [
                    'notification_id' => $notification->id
                ]);
            }
        }

        return $processedCount;
    }

    /**
     * Foydalanuvchi bildirishnomalarini olish
     */
    public function getUserNotifications(User $user, int $limit = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Notification::where('user_id', $user->id)
                          ->where('channel', 'database')
                          ->orderBy('created_at', 'desc')
                          ->paginate($limit);
    }

    /**
     * O'qilmagan bildirishnomalar sonini olish
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
                          ->where('channel', 'database')
                          ->unread()
                          ->count();
    }
}
