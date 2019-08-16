<?php
/**
 * Copyright (c) 2014-2015 BitPay
 */

/**
 * WARNING - This example will NOT work until you have generated your public
 * keys and also see the documentation on how to save those keys.
 *
 * Also please be aware that you CANNOT create an invoice until you have paired
 * the keys and received a token back. The token is usesd with the request.
 */

$key_dir = '/tmp';
require __DIR__ . '/../../vendor/autoload.php';

$time = gmdate("Y-m-d\TH:i:s\.", 1414691179) . "000Z";

$token = new \BTCPayServer\Token();
$token->setFacade('payroll')->setToken('your token goes here'); //this is a special api that requires a explicit payroll relationship with BitPay

$instruction1 = new \BTCPayServer\PayoutInstruction();
$instruction1->setAmount(100)->setAddress('2NA5EVH9HHHhM5RxSEWf54gP4v397EmFTxi')->setLabel('Paying Chris');

$payout = new \BTCPayServer\Payout();
$payout->setEffectiveDate($time)->setAmount(100)->setCurrency(new \BTCPayServer\Currency('USD'))->setPricingMethod('bitcoinbestbuy')->setReference('a reference, can be json')->setNotificationEmail('an email goes here')->setNotificationUrl('https://example.com/ipn.php')->setToken($token)->addInstruction($instruction1);


//this is your private key in some form (see GetKeys.php)
$storageEngine = new \BTCPayServer\Storage\EncryptedFilesystemStorage('TopSecretPassword');
$private = $storageEngine->load($key_dir . '/bitpay.pri');
$public = $storageEngine->load($key_dir . '/bitpay.pub');


//$network = new \BTCPayServer\Network\Testnet();
$adapter = new \BTCPayServer\Client\Adapter\CurlAdapter();


$btcpay = new \BTCPayServer\BTCPayServer();

$client = new \BTCPayServer\Client\Client();
$client->setPrivateKey($private);
$client->setPublicKey($public);
$client->setUri('https://my-btcpay-server.com');
$client->setAdapter($adapter);

$client->createPayout($payout);

print_r($payout);
