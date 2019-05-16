<?php
/**
 * Copyright (c) 2014-2017 BTCPayServer
 *
 * getInvoice
 *
 * Requirements:
 *   - Account on https://testnet.demo.btcpayserver.org
 *   - Baisic PHP Knowledge
 *   - Private and Public keys from 001.php
 *   - Token value obtained from 002.php
 *   - Invoice created
 */
require __DIR__.'/../../vendor/autoload.php';

// Now fetch the invoice from BTCPayServer

$storageEngine = new \BTCPayServer\Storage\EncryptedFilesystemStorage('YourTopSecretPassword');
$privateKey    = $storageEngine->load('/tmp/btcpayserver.pri');
$publicKey     = $storageEngine->load('/tmp/btcpayserver.pub');
$client        = new \BTCPayServer\Client\Client();
$adapter       = new \BTCPayServer\Client\Adapter\CurlAdapter();
$client->setPrivateKey($privateKey);
$client->setPublicKey($publicKey);
$client->setUri('https://testnet.demo.btcpayserver.org');
$client->setAdapter($adapter);

$token = new \BTCPayServer\Token();
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
