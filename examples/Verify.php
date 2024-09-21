<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\VerifyRequest;

// ایجاد ClientBuilder برای تنظیم هدرهای پیش‌فرض
$clientBuilder = new ClientBuilder();
$clientBuilder->addPlugin(new HeaderDefaultsPlugin([
    'Accept' => 'application/json',
]));

// پیکربندی SDK
$options = new Options([
    'client_builder' => $clientBuilder,
    'sandbox' => false, // غیرفعال‌سازی حالت تستی برای تراکنش‌های واقعی
    'merchant_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', // Merchant ID شما
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

// دریافت authority و status از کوئری استرینگ
$authority = $_GET['Authority'];
$status = $_GET['Status'];

// بررسی وضعیت تراکنش
if ($status === 'OK') {
    // جستجوی مبلغ تراکنش در دیتابیس بر اساس authority
    // این قسمت باید جستجوی مبلغ مربوط به authority در دیتابیس شما انجام شود
    $amount = getAmountFromDatabase($authority); // تابع فرضی برای دریافت مبلغ از دیتابیس

    if ($amount) {
        // ایجاد درخواست برای تأیید پرداخت
        $verifyRequest = new VerifyRequest();
        $verifyRequest->authority = $authority; // استفاده از authority دریافتی از درگاه
        $verifyRequest->amount = $amount; // ارسال مبلغ پیدا شده از دیتابیس

        try {
            // ارسال درخواست تأیید پرداخت به زرین‌پال
            $response = $paymentGateway->verify($verifyRequest);

            // بررسی وضعیت تراکنش با کدهای 100 و 101
            if ($response->code === 100 || $response->code === 101) {
                // تراکنش موفق است، نمایش اطلاعات تراکنش به کاربر
                echo "Payment Verified: \n";
                echo "Reference ID: " . $response->ref_id . "\n";
                echo "Card PAN: " . $response->card_pan . "\n";
                echo "Fee: " . $response->fee . "\n";
            } else {
                // نمایش خطای تراکنش ناموفق
                echo "Transaction failed with code: " . $response->code;
            }

        } catch (\Exception $e) {
            // مدیریت خطا در صورت ناموفق بودن تأیید پرداخت
            echo 'Payment verification failed: ' . $e->getMessage();
        }
    } else {
        echo 'No matching transaction found for this authority code.';
    }
} else {
    // اگر وضعیت NOK باشد، تراکنش ناموفق یا لغو شده است
    echo 'Transaction was cancelled or failed.';
}
