<?php
require_once 'gbprimepay.php';
$token = "TOKEN";
// $public_key = "PUBLIC_KEY";
// $secret_key = "SECRET_KEY";
$gbprimepay = new GBPrimePay();
$qrcode = $gbprimepay->promptpay([
    'amount' => '10.00',
    'referenceNo' => 'PP1234',
    'backgroundUrl' => 'https://dev.0x01code.me/gbprimepay.webhook.php',
], $token);
echo '<img src="' . $qrcode . '">';
// echo $gbprimepay->truewallet([
//     'amount' => '10.00',
//     'referenceNo' => 'TW1234',
//     'backgroundUrl' => 'https://dev.0x01code.me/gbprimepay.webhook.php',
//     'responseUrl' => 'https://dev.0x01code.me/thankyou.php',
//     'customerTelephone' => '0600000000',
// ], $public_key, $secret_key);
// echo $gbprimepay->linepay([
//     'amount' => '10.00',
//     'referenceNo' => 'LP1234',
//     'detail' => 'test',
//     'responseUrl' => 'https://dev.0x01code.me/thankyou.php',
//     'backgroundUrl' => 'https://dev.0x01code.me/gbprimepay.webhook.php',
// ], $public_key, $secret_key);
// echo $gbprimepay->shopeepay([
//     'amount' => '10.00',
//     'referenceNo' => 'SP1234',
//     'responseUrl' => 'https://dev.0x01code.me/thankyou.php',
//     'backgroundUrl' => 'https://dev.0x01code.me/gbprimepay.webhook.php',
// ], $public_key, $secret_key);
