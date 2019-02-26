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
require __DIR__.'/../../vendor/autoload.php';

// Now fetch the invoice from BitPay

$storageEngine = new \Bitpay\Storage\EncryptedFilesystemStorage('YourTopSecretPassword');
$privateKey    = $storageEngine->load('/tmp/bitpay.pri');
$publicKey     = $storageEngine->load('/tmp/bitpay.pub');
$client        = new \Bitpay\Client\Client();
$adapter       = new \Bitpay\Client\Adapter\CurlAdapter();
$client->setPrivateKey($privateKey);
$client->setPublicKey($publicKey);
$client->setUri('https://btcpay.server/');
$client->setAdapter($adapter);

$token = new \Bitpay\Token();
$token->setToken('UpdateThisValue'); // UPDATE THIS VALUE
$token->setFacade('merchant');

$client->setToken($token);

/**
 * This is where we will fetch the invoice object
 */
$invoice = $client->getInvoice("UpdateThisValue");

$request  = $client->getRequest();
$response = $client->getResponse();
echo (string) $request.PHP_EOL.PHP_EOL.PHP_EOL;
echo (string) $response.PHP_EOL.PHP_EOL;

print_r($invoice);
