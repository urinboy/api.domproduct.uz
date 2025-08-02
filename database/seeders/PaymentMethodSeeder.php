<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            [
                'name' => 'Naqd pul',
                'code' => 'cash',
                'display_name' => 'Naqd pul bilan to\'lov',
                'description' => 'Yetkazib berish paytida naqd pul bilan to\'lov',
                'is_active' => true,
                'is_online' => false,
                'sort_order' => 1,
                'min_amount' => 1000.00,
                'max_amount' => 5000000.00,
                'fee_percentage' => 0.00,
                'fee_fixed' => 0.00,
                'config' => null,
                'supported_currencies' => ['UZS'],
                'icon_url' => '/images/payment/cash.png',
                'color_scheme' => '#28a745',
            ],
            [
                'name' => 'Click',
                'code' => 'click',
                'display_name' => 'Click orqali to\'lov',
                'description' => 'Click mobile ilovasi orqali to\'lov',
                'is_active' => true,
                'is_online' => true,
                'sort_order' => 2,
                'min_amount' => 1000.00,
                'max_amount' => 5000000.00,
                'fee_percentage' => 0.00,
                'fee_fixed' => 3000.00,
                'config' => [
                    'gateway' => 'click',
                    'service_id' => 'test_service',
                    'merchant_id' => 'test_merchant',
                    'test_mode' => true,
                ],
                'gateway_url' => 'https://api.click.uz/v2/merchant',
                'webhook_url' => 'https://api.domproduct.uz/webhooks/click',
                'supported_currencies' => ['UZS'],
                'icon_url' => '/images/payment/click.png',
                'logo_url' => '/images/payment/click_logo.png',
                'color_scheme' => '#00AEEF',
            ],
            [
                'name' => 'Payme',
                'code' => 'payme',
                'display_name' => 'Payme orqali to\'lov',
                'description' => 'Payme mobile ilovasi orqali to\'lov',
                'is_active' => true,
                'is_online' => true,
                'sort_order' => 3,
                'min_amount' => 1000.00,
                'max_amount' => 5000000.00,
                'fee_percentage' => 1.0,
                'fee_fixed' => 0.00,
                'config' => [
                    'gateway' => 'payme',
                    'merchant_id' => 'test_merchant',
                    'test_mode' => true,
                ],
                'gateway_url' => 'https://checkout.paycom.uz',
                'webhook_url' => 'https://api.domproduct.uz/webhooks/payme',
                'supported_currencies' => ['UZS'],
                'icon_url' => '/images/payment/payme.png',
                'logo_url' => '/images/payment/payme_logo.png',
                'color_scheme' => '#14E5E4',
            ],
            [
                'name' => 'UzCard',
                'code' => 'uzcard',
                'display_name' => 'UzCard orqali to\'lov',
                'description' => 'UzCard plastik kartasi orqali to\'lov',
                'is_active' => true,
                'is_online' => true,
                'sort_order' => 4,
                'min_amount' => 1000.00,
                'max_amount' => 3000000.00,
                'fee_percentage' => 1.5,
                'fee_fixed' => 0.00,
                'config' => [
                    'gateway' => 'uzcard',
                    'merchant_id' => 'test_merchant',
                    'test_mode' => true,
                ],
                'gateway_url' => 'https://api.uzcard.uz',
                'webhook_url' => 'https://api.domproduct.uz/webhooks/uzcard',
                'supported_currencies' => ['UZS'],
                'icon_url' => '/images/payment/uzcard.png',
                'logo_url' => '/images/payment/uzcard_logo.png',
                'color_scheme' => '#1E3A8A',
            ],
            [
                'name' => 'Apelsin',
                'code' => 'apelsin',
                'display_name' => 'Apelsin orqali to\'lov',
                'description' => 'Apelsin mobile ilovasi orqali to\'lov',
                'is_active' => true,
                'is_online' => true,
                'sort_order' => 5,
                'min_amount' => 1000.00,
                'max_amount' => 2000000.00,
                'fee_percentage' => 2.0,
                'fee_fixed' => 0.00,
                'config' => [
                    'gateway' => 'apelsin',
                    'merchant_id' => 'test_merchant',
                    'test_mode' => true,
                ],
                'gateway_url' => 'https://api.apelsin.uz',
                'webhook_url' => 'https://api.domproduct.uz/webhooks/apelsin',
                'supported_currencies' => ['UZS'],
                'icon_url' => '/images/payment/apelsin.png',
                'logo_url' => '/images/payment/apelsin_logo.png',
                'color_scheme' => '#FF6B35',
            ],
            [
                'name' => 'Bank o\'tkazmasi',
                'code' => 'bank_transfer',
                'display_name' => 'Bank orqali o\'tkazma',
                'description' => 'Bank orqali o\'tkazma (Manual)',
                'is_active' => true,
                'is_online' => false,
                'sort_order' => 6,
                'min_amount' => 10000.00,
                'max_amount' => 50000000.00,
                'fee_percentage' => 0.5,
                'fee_fixed' => 5000.00,
                'config' => [
                    'bank_name' => 'TBC Bank',
                    'account_number' => '20208000804280000001',
                    'swift_code' => 'TBCBUZ22',
                    'account_holder' => 'DomProduct LLC',
                ],
                'supported_currencies' => ['UZS', 'USD'],
                'icon_url' => '/images/payment/bank.png',
                'color_scheme' => '#6C757D',
            ],
            [
                'name' => 'Plastik karta',
                'code' => 'card',
                'display_name' => 'Plastik karta',
                'description' => 'Visa/MasterCard orqali to\'lov',
                'is_active' => true,
                'is_online' => true,
                'sort_order' => 7,
                'min_amount' => 1000.00,
                'max_amount' => 10000000.00,
                'fee_percentage' => 2.5,
                'fee_fixed' => 0.00,
                'config' => [
                    'gateway' => 'stripe',
                    'merchant_id' => 'test_merchant',
                    'test_mode' => true,
                ],
                'gateway_url' => 'https://api.stripe.com',
                'webhook_url' => 'https://api.domproduct.uz/webhooks/stripe',
                'supported_currencies' => ['UZS', 'USD'],
                'icon_url' => '/images/payment/card.png',
                'color_scheme' => '#007BFF',
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }

        $this->command->info('Payment methods seeded successfully!');
    }
}
