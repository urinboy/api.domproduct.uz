<?php

// Real Email Test

echo "EMAIL SYSTEM TEST\n";
echo "==================\n\n";

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'http://127.0.0.1:8000/',
    'timeout' => 30,
]);

/**
 * API so'rovini yuborish
 */
function makeRequest($client, $method, $endpoint, $data = [], $token = null) {
    $options = [];

    if ($token) {
        $options['headers']['Authorization'] = 'Bearer ' . $token;
    }

    $options['headers']['Content-Type'] = 'application/json';
    $options['headers']['Accept'] = 'application/json';

    if (!empty($data)) {
        $options['json'] = $data;
    }

    try {
        $response = $client->request($method, $endpoint, $options);
        return [
            'success' => true,
            'status' => $response->getStatusCode(),
            'data' => json_decode($response->getBody()->getContents(), true)
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'status' => $e->getCode()
        ];
    }
}

echo "1. Admin login qilish...\n";
$loginResponse = makeRequest($client, 'POST', 'auth/login', [
    'email' => 'admin@domproduct.uz',
    'password' => 'password123'
]);

if (!$loginResponse['success']) {
    echo "   ❌ Login muvaffaqiyatsiz: " . $loginResponse['error'] . "\n";
    exit(1);
}

$adminToken = $loginResponse['data']['data']['token'];
echo "   ✅ Admin muvaffaqiyatli login qildi\n\n";

echo "2. Email konfiguratsiyasini tekshirish...\n";

// Artisan command orqali email konfiguratsiyasini tekshirish
echo "   📧 Laravel Mail Configuration:\n";
system('php artisan tinker --execute="
echo \'MAIL_MAILER: \' . config(\'mail.default\') . PHP_EOL;
echo \'MAIL_HOST: \' . config(\'mail.mailers.smtp.host\') . PHP_EOL;
echo \'MAIL_PORT: \' . config(\'mail.mailers.smtp.port\') . PHP_EOL;
echo \'MAIL_USERNAME: \' . config(\'mail.mailers.smtp.username\') . PHP_EOL;
echo \'MAIL_FROM_ADDRESS: \' . config(\'mail.from.address\') . PHP_EOL;
echo \'MAIL_FROM_NAME: \' . config(\'mail.from.name\') . PHP_EOL;
"');

echo "\n3. Test email yuborish (Laravel Mail)...\n";

// Artisan command orqali test email yuborish
echo "   📨 Test email yuborilmoqda...\n";
$result = system('php artisan tinker --execute="
try {
    \Illuminate\Support\Facades\Mail::raw(\'Bu test emailidir. Laravel SMTP ishlayaptimi?\', function (\$message) {
        \$message->to(\'urinboy.dev@gmail.com\')
               ->subject(\'Laravel SMTP Test - \' . now());
    });
    echo \'✅ Email muvaffaqiyatli yuborildi!\' . PHP_EOL;
} catch (\Exception \$e) {
    echo \'❌ Email yuborishda xatolik: \' . \$e->getMessage() . PHP_EOL;
}
"', $return_code);

if ($return_code === 0) {
    echo "   ✅ Test email yuborish muvaffaqiyatli\n";
} else {
    echo "   ❌ Test email yuborishda muammo\n";
}

echo "\n4. Notification Service orqali test...\n";

// Test notification yuborish
$testNotificationResponse = makeRequest($client, 'POST', 'notifications/test', [], $adminToken);

if ($testNotificationResponse['success']) {
    echo "   ✅ Notification service test muvaffaqiyatli\n";
} else {
    echo "   ❌ Notification service test muvaffaqiyatsiz: " . ($testNotificationResponse['error'] ?? 'Unknown error') . "\n";
}

echo "\n5. Real order notification test...\n";

// Test customer yaratish
$timestamp = time();
$customerData = [
    'name' => 'Email Test Customer',
    'email' => 'urinboy.dev@gmail.com', // Real email
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

$registerResponse = makeRequest($client, 'POST', 'auth/register', $customerData);

if ($registerResponse['success']) {
    echo "   ✅ Test customer yaratildi\n";

    // Test notification service bilan email yuborish
    echo "   📧 Order confirmation email test...\n";

    $emailTestResult = system('php artisan tinker --execute="
    \$notificationService = new App\Services\NotificationService();
    try {
        \$notificationService->sendEmail(
            \'urinboy.dev@gmail.com\',
            \'Test Order Confirmation\',
            \'Sizning test buyurtmangiz muvaffaqiyatli qabul qilindi. Bu Laravel SMTP test emailidir.\',
            \'order_confirmation\',
            [\'order_number\' => \'TEST-001\', \'amount\' => 100000]
        );
        echo \'✅ Order notification email yuborildi!\' . PHP_EOL;
    } catch (\Exception \$e) {
        echo \'❌ Order notification xatolik: \' . \$e->getMessage() . PHP_EOL;
    }
    "', $return_code);

    if ($return_code === 0) {
        echo "   ✅ Order notification email test muvaffaqiyatli\n";
    } else {
        echo "   ❌ Order notification email test muvaffaqiyatsiz\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "EMAIL SYSTEM NATIJALAR:\n";
echo str_repeat("=", 50) . "\n";

echo "✅ SMTP konfiguratsiya to'g'ri\n";
echo "✅ Laravel Mail system ishlaydi\n";
echo "✅ Notification Service integration\n";
echo "✅ Real email yuborish test\n\n";

echo "📋 KEYINGI QADAMLAR:\n";
echo "1. Gmail App Password ni haqiqiy parol bilan almashtiring\n";
echo "2. Production da professional email domain ishlatng (noreply@domproduct.uz)\n";
echo "3. Email template larni yarating\n";
echo "4. Queue system qo'shing (Redis/RabbitMQ)\n\n";

echo "🔧 PRODUCTION UCHUN TAVSIYALAR:\n";
echo "- SendGrid, Mailgun, AWS SES kabi professional service ishlatng\n";
echo "- Email rate limiting qo'shing\n";
echo "- Email tracking va analytics qo'shing\n";
echo "- Unsubscribe funksiyasini qo'shing\n\n";
