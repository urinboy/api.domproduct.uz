<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|max:255',
                'name' => 'nullable|string|max:255'
            ]);

            // Check if already exists
            $existingSubscription = NewsletterSubscription::where('email', $request->email)->first();

            if ($existingSubscription) {
                if ($existingSubscription->status === NewsletterSubscription::STATUS_UNSUBSCRIBED) {
                    // Reactivate subscription
                    $existingSubscription->resubscribe();
                    $existingSubscription->refresh(); // Reload from database

                    return response()->json([
                        'success' => true,
                        'message' => 'Siz yana obuna bo\'ldingiz!',
                        'data' => [
                            'email' => $existingSubscription->email,
                            'status' => $existingSubscription->status,
                            'subscribed_at' => $existingSubscription->subscribed_at
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Siz allaqachon obuna bo\'lgansiz!',
                        'errors' => [
                            'email' => ['Bu email allaqachon obuna bo\'lgan.']
                        ]
                    ], 409);
                }
            }

            // Create new subscription
            $subscription = NewsletterSubscription::create([
                'email' => $request->email,
                'name' => $request->name,
                'status' => NewsletterSubscription::STATUS_ACTIVE,
                'subscribed_at' => Carbon::now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
                'preferences' => []
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Muvaffaqiyatli obuna bo\'ldingiz!',
                'data' => [
                    'email' => $subscription->email,
                    'status' => $subscription->status,
                    'subscribed_at' => $subscription->subscribed_at
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validatsiya xatoliklari.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Newsletter subscription error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi. Qaytadan urinib ko\'ring.',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $subscription = NewsletterSubscription::where('email', $request->email)->first();

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu email bilan obuna topilmadi.',
                    'errors' => [
                        'email' => ['Bu email bilan obuna mavjud emas.']
                    ]
                ], 404);
            }

            if ($subscription->status === NewsletterSubscription::STATUS_UNSUBSCRIBED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siz allaqachon obunani bekor qilgansiz.',
                    'data' => [
                        'email' => $subscription->email,
                        'status' => $subscription->status,
                        'unsubscribed_at' => $subscription->unsubscribed_at
                    ]
                ], 409);
            }

            // Unsubscribe
            $subscription->unsubscribe();

            return response()->json([
                'success' => true,
                'message' => 'Siz muvaffaqiyatli obunani bekor qildingiz.',
                'data' => [
                    'email' => $subscription->email,
                    'status' => $subscription->status,
                    'unsubscribed_at' => $subscription->unsubscribed_at
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validatsiya xatoliklari.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi. Qaytadan urinib ko\'ring.',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get subscription status
     */
    public function status(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $subscription = NewsletterSubscription::where('email', $request->email)->first();

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu email bilan obuna topilmadi.',
                    'data' => [
                        'subscribed' => false,
                        'status' => null
                    ]
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Obuna statusi olindi.',
                'data' => [
                    'email' => $subscription->email,
                    'subscribed' => $subscription->isActive(),
                    'status' => $subscription->status,
                    'subscribed_at' => $subscription->subscribed_at,
                    'unsubscribed_at' => $subscription->unsubscribed_at
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validatsiya xatoliklari.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi. Qaytadan urinib ko\'ring.',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }
}
