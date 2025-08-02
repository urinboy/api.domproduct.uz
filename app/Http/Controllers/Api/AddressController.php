<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Foydalanuvchining barcha manzillarini olish
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $addresses = Address::where('user_id', $user->id)
                ->where('is_active', true)
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Manzillar muvaffaqiyatli olindi',
                'data' => [
                    'addresses' => $addresses->map(function ($address) {
                        return [
                            'id' => $address->id,
                            'type' => $address->type,
                            'first_name' => $address->first_name,
                            'last_name' => $address->last_name,
                            'full_name' => $address->full_name,
                            'company' => $address->company,
                            'phone' => $address->phone,
                            'email' => $address->email,
                            'country' => $address->country,
                            'region' => $address->region,
                            'city' => $address->city,
                            'district' => $address->district,
                            'street_address' => $address->street_address,
                            'apartment' => $address->apartment,
                            'postal_code' => $address->postal_code,
                            'coordinates' => $address->coordinates,
                            'delivery_instructions' => $address->delivery_instructions,
                            'is_default' => $address->is_default,
                            'formatted_address' => $address->full_address,
                            'created_at' => $address->created_at,
                        ];
                    }),
                    'default_billing' => $addresses->where('type', Address::TYPE_BILLING)->where('is_default', true)->first(),
                    'default_shipping' => $addresses->where('type', Address::TYPE_SHIPPING)->where('is_default', true)->first(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Manzillarni olishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Yangi manzil yaratish
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|in:billing,shipping,both',
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'company' => 'sometimes|nullable|string|max:200',
                'phone' => 'required|string|max:20',
                'email' => 'sometimes|nullable|email|max:100',
                'country' => 'required|string|max:100',
                'region' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'district' => 'sometimes|nullable|string|max:100',
                'street_address' => 'required|string|max:255',
                'apartment' => 'sometimes|nullable|string|max:50',
                'postal_code' => 'sometimes|nullable|string|max:20',
                'latitude' => 'sometimes|nullable|numeric|between:-90,90',
                'longitude' => 'sometimes|nullable|numeric|between:-180,180',
                'delivery_instructions' => 'sometimes|nullable|string|max:500',
                'is_default' => 'sometimes|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validatsiya xatosi',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            DB::beginTransaction();

            try {
                $address = Address::create([
                    'user_id' => $user->id,
                    'type' => $request->type,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'company' => $request->company,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'country' => $request->country,
                    'region' => $request->region,
                    'city' => $request->city,
                    'district' => $request->district,
                    'street_address' => $request->street_address,
                    'apartment' => $request->apartment,
                    'postal_code' => $request->postal_code,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'delivery_instructions' => $request->delivery_instructions,
                    'is_default' => $request->is_default ?? false,
                    'is_active' => true,
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Manzil muvaffaqiyatli yaratildi',
                    'data' => [
                        'address' => [
                            'id' => $address->id,
                            'type' => $address->type,
                            'full_name' => $address->full_name,
                            'phone' => $address->phone,
                            'formatted_address' => $address->full_address,
                            'is_default' => $address->is_default,
                            'created_at' => $address->created_at,
                        ]
                    ]
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Manzil yaratishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manzil tafsilotlarini olish
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();

            $address = Address::where('id', $id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Manzil muvaffaqiyatli olindi',
                'data' => [
                    'address' => [
                        'id' => $address->id,
                        'type' => $address->type,
                        'first_name' => $address->first_name,
                        'last_name' => $address->last_name,
                        'full_name' => $address->full_name,
                        'company' => $address->company,
                        'phone' => $address->phone,
                        'email' => $address->email,
                        'country' => $address->country,
                        'region' => $address->region,
                        'city' => $address->city,
                        'district' => $address->district,
                        'street_address' => $address->street_address,
                        'apartment' => $address->apartment,
                        'postal_code' => $address->postal_code,
                        'coordinates' => $address->coordinates,
                        'delivery_instructions' => $address->delivery_instructions,
                        'is_default' => $address->is_default,
                        'formatted_address' => $address->formatted_address,
                        'created_at' => $address->created_at,
                        'updated_at' => $address->updated_at,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Manzil topilmadi',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Manzilni yangilash
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'sometimes|string|in:billing,shipping,both',
                'first_name' => 'sometimes|string|max:100',
                'last_name' => 'sometimes|string|max:100',
                'company' => 'sometimes|nullable|string|max:200',
                'phone' => 'sometimes|string|max:20',
                'email' => 'sometimes|nullable|email|max:100',
                'country' => 'sometimes|string|max:100',
                'region' => 'sometimes|string|max:100',
                'city' => 'sometimes|string|max:100',
                'district' => 'sometimes|nullable|string|max:100',
                'street_address' => 'sometimes|string|max:255',
                'apartment' => 'sometimes|nullable|string|max:50',
                'postal_code' => 'sometimes|nullable|string|max:20',
                'latitude' => 'sometimes|nullable|numeric|between:-90,90',
                'longitude' => 'sometimes|nullable|numeric|between:-180,180',
                'delivery_instructions' => 'sometimes|nullable|string|max:500',
                'is_default' => 'sometimes|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validatsiya xatosi',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            $address = Address::where('id', $id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->firstOrFail();

            DB::beginTransaction();

            try {
                $address->update($request->only([
                    'type', 'first_name', 'last_name', 'company', 'phone', 'email',
                    'country', 'region', 'city', 'district', 'street_address',
                    'apartment', 'postal_code', 'latitude', 'longitude',
                    'delivery_instructions', 'is_default'
                ]));

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Manzil muvaffaqiyatli yangilandi',
                    'data' => [
                        'address' => [
                            'id' => $address->id,
                            'type' => $address->type,
                            'full_name' => $address->full_name,
                            'phone' => $address->phone,
                            'formatted_address' => $address->full_address,
                            'is_default' => $address->is_default,
                            'updated_at' => $address->updated_at,
                        ]
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Manzilni yangilashda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manzilni o'chirish (soft delete)
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();

            $address = Address::where('id', $id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->firstOrFail();

            // Default manzilni o'chirishga ruxsat bermaymiz
            if ($address->is_default) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asosiy manzilni o\'chirib bo\'lmaydi. Avval boshqa manzilni asosiy qilib belgilang.'
                ], 422);
            }

            DB::beginTransaction();

            try {
                $address->update(['is_active' => false]);
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Manzil muvaffaqiyatli o\'chirildi'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Manzilni o\'chirishda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manzilni asosiy qilib belgilash
     */
    public function setDefault(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();

            $address = Address::where('id', $id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->firstOrFail();

            DB::beginTransaction();

            try {
                // Eski default manzilni bekor qilish
                Address::where('user_id', $user->id)
                    ->where('type', $address->type)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);

                // Yangi default manzilni belgilash
                $address->update(['is_default' => true]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Manzil asosiy manzil sifatida belgilandi',
                    'data' => [
                        'address' => [
                            'id' => $address->id,
                            'type' => $address->type,
                            'full_name' => $address->full_name,
                            'formatted_address' => $address->full_address,
                            'is_default' => $address->is_default,
                        ]
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Asosiy manzilni belgilashda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Yetkazib berish narxini hisoblash
     */
    public function calculateDeliveryFee(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'delivery_method' => 'required|string|in:standard,express',
                'total_amount' => 'sometimes|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validatsiya xatosi',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            $address = Address::where('id', $id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->firstOrFail();

            $deliveryFee = $this->calculateDeliveryFeeForAddress(
                $address,
                $request->delivery_method,
                $request->total_amount ?? 0
            );

            return response()->json([
                'success' => true,
                'message' => 'Yetkazib berish narxi hisoblandi',
                'data' => [
                    'address_id' => $address->id,
                    'delivery_method' => $request->delivery_method,
                    'delivery_fee' => $deliveryFee,
                    'currency' => 'UZS',
                    'address_info' => [
                        'city' => $address->city,
                        'region' => $address->region,
                        'district' => $address->district,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yetkazib berish narxini hisoblashda xatolik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manzil uchun yetkazib berish narxini hisoblash
     */
    private function calculateDeliveryFeeForAddress($address, $deliveryMethod, $totalAmount = 0): float
    {
        $baseFee = 15000; // Standard yetkazib berish

        // Express yetkazib berish qimmatlashadi
        if ($deliveryMethod === 'express') {
            $baseFee = 25000;
        }

        // Shahar bo'yicha narx farqi
        $cityMultiplier = 1.0;
        $city = strtolower($address->city);

        if (in_array($city, ['toshkent', 'tashkent'])) {
            $cityMultiplier = 1.0; // Asosiy narx
        } elseif (in_array($city, ['samarqand', 'buxoro', 'navoiy', 'nukus'])) {
            $cityMultiplier = 1.5; // 50% qo'shimcha
        } else {
            $cityMultiplier = 2.0; // Boshqa shaharlar uchun 100% qo'shimcha
        }

        $finalFee = $baseFee * $cityMultiplier;

        // Agar buyurtma summasi katta bo'lsa, chegirma berish
        if ($totalAmount >= 500000) { // 500,000 UZS dan yuqori
            $finalFee *= 0.5; // 50% chegirma
        } elseif ($totalAmount >= 200000) { // 200,000 UZS dan yuqori
            $finalFee *= 0.7; // 30% chegirma
        }

        return round($finalFee);
    }
}
