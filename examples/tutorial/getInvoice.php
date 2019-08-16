<?php
/**
 * Copyright (c) 2014-2017 BitPay
 *
 * getInvoice
 *
 * Requirements:
 *   - Account on https://test.bitpay.com
 *   - Baisic PHP Knowledge
 *   - Private and Public keys from 001.php
 *   - Token value obtained from 002.php
 *   - Invoice created
 */
require __DIR__ . '/../../vendor/autoload.php';

// Now fetch the invoice from BitPay

$client = new \BTCPayServer\Client\Client();
$adapter = new \BTCPayServer\Client\Adapter\CurlAdapter();
$client->setUri('https://my-btcpay-server.com');
$client->setAdapter($adapter);

$token = new \BTCPayServer\Token();
$token->setToken('UpdateThisValue'); // UPDATE THIS VALUE

$client->setToken($token);

/**
 * This is where we will fetch the invoice object
 */
$invoice = $client->getInvoice("UpdateThisValue");

$request = $client->getRequest();
$response = $client->getResponse();
echo (string)$request . PHP_EOL . PHP_EOL . PHP_EOL;
echo (string)$response . PHP_EOL . PHP_EOL;

print_r($invoice);

?>