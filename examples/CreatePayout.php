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

require __DIR__ . '/../vendor/autoload.php';

$time = gmdate("Y-m-d\TH:i:s\.", 1414691179) . "000Z";

$token = new \BTCPayServer\Token();
$token->setFacade('payroll')->setToken('<api token>'); //this is a special api that requires a explicit payroll relationship with BitPay

$instruction1 = new \BTCPayServer\PayoutInstruction();
$instruction1->setAmount(100)->setAddress('2NA5EVH9HHHhM5RxSEWf54gP4v397EmFTxi')->setLabel('Paying Someone');

$payout = new \BTCPayServer\Payout();
$payout->setEffectiveDate($time)->setAmount(100)->setCurrency(new \BTCPayServer\Currency('USD'))->setPricingMethod('bitcoinbestbuy')->setReference('a reference, can be json')->setNotificationEmail('joshlewis@gmail.com')->setNotificationUrl('https://example.com/ipn.php')->setToken($token)->addInstruction($instruction1);

#$private = new \BTCPayServer\PrivateKey();
#$private->setHex('5555550968bc659873d723374213fa5bf7a30c24f0f0713aa798eb7daa7230fc'); 
//this is your private key in some form (see GetKeys.php)

#$public = new \BTCPayServer\PublicKey();
#$public->generate($private);

$storageEngine = new \BTCPayServer\Storage\EncryptedFilesystemStorage('TopSecretPassword');
$private = $storageEngine->load('/tmp/private_key.key');
#$public     = $storageEngine->load('/tmp/public_key.key');
#error_log('$private '.$private);
$public = new \BTCPayServer\PublicKey();
$public->generate($private);

$adapter = new \BTCPayServer\Client\Adapter\CurlAdapter();


$btcpay = new \BTCPayServer\BTCPayServer();

$client = new \BTCPayServer\Client\Client();
$client->setPrivateKey($private);
$client->setPublicKey($public);
$client->setAdapter($adapter);

$client->createPayout($payout);

print_r($payout);
