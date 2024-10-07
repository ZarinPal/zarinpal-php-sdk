<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\RequestRequest;

$clientBuilder = new ClientBuilder();
$clientBuilder->addPlugin(new HeaderDefaultsPlugin([
    'Accept' => 'application/json',
]));

$options = new Options([
    'client_builder' => $clientBuilder,
    'sandbox' => false, // Enable sandbox mode
    'merchant_id' => '67887a6d-e2f8-4de2-86b1-8db27bc171b5',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$request = new RequestRequest();
$request->amount = 10000; //Minimum amount 10000 IRR
$request->description = 'Payment for order 12345';
$request->callback_url = 'https://your-site.test/examples/verify.php';
$request->mobile = '09120987654'; // Optional
$request->email = 'test@example.com'; // Optional
$request->currency = 'IRR'; // Optional IRR Or IRT (default IRR)
$request->referrer_id = 'GYKCZDF'; // Optional IRR Or IRT (default IRR)
$request->cardPan = '5894631122689480'; // Optional
$request->wages = [
    [
        'iban' => 'IR130570028780010957775102',
        'amount' =>5000,
        'description' => 'تسهیم سود فروش'
    ],
    [
        'iban' => 'IR670170000000352965862005',
        'amount' => 5000,
        'description' => 'تسهیم سود فروش به شخص دوم'
    ]
]; //Optional

try {
    $response = $paymentGateway->request($request);
    $url = $paymentGateway->getRedirectUrl($response->authority); // create full url Payment
    header('Location:'. $url);

} catch (ResponseException $e) {
    var_dump($e->getErrorDetails());
} catch (\Exception $e) {
    echo 'Payment Error: ' . $e->getMessage();
}
