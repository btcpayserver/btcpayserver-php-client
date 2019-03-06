<?php

require __DIR__ . '/../vendor/autoload.php';


$storageEngine = new \BTCPayServer\Storage\FilesystemStorage();

$private = $storageEngine->load('/tmp/private.key');
$public  = new \BTCPayServer\PublicKey('/tmp/public.key');

$public->setPrivateKey($private);
$public->generate();

printf("Public Key:  %s\n", $public);
printf("Private Key: %s\n", $private);

$message = 'https://test.btcpayserver.com/subscriptions{"schedule":"weekly","token":"some token","billData":{"currency":"USD","price":"2.00","quantity":1}}';

$signedMessage = $private->sign($message);
print_r('message to be signed:: ' . $message . "\n");
print_r('signed message:: ' . $signedMessage);

