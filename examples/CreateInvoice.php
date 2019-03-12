<?php
/**
 * Copyright (c) 2014-2015 BTCPayServer
 *
 * WARNING - This example will NOT work until you have generated your public
 * and private keys. Please see the example documentation on generating your
 * keys and also see the documentation on how to save those keys.
 *
 * Also please be aware that you CANNOT create an invoice until you have paired
 * the keys and received a token back. The token is usesd with the request.
 */

require __DIR__ . '/../vendor/autoload.php';

/**
 * Create an Item object that will be used later
 */
$item = new \BTCPayServer\Item();
$item
    ->setCode('skuNumber')
    ->setDescription('General Description of Item')
    ->setPrice('1.99');

/**
 * Create Buyer object that will be used later.
 */
$buyer = new \BTCPayServer\Buyer();
$buyer
    ->setFirstName('Some')
    ->setLastName('Customer')
    ->setPhone('555-5555-5555')
    ->setEmail('test@test.com')
    ->setAddress(
        array(
            '123 Main St',
            'Suite 1',
        )
    )
    ->setCity('Atlanta')
    ->setState('GA')
    ->setZip('30120')
    ->setCountry('US');

/**
 * Create the invoice
 */
$invoice = new \BTCPayServer\Invoice();
// Add the item to the invoice
$invoice->setItem($item);
// Add the buyers info to invoice
$invoice->setBuyer($buyer);
// Configure the rest of the invoice
$invoice
    ->setOrderId('OrderIdFromYourSystem')
    // You will receive IPN's at this URL, should be HTTPS for security purposes!
    ->setNotificationUrl('https://store.example.com/btcpayserver/callback');

/**
 * BTCPayServer offers services for many different currencies. You will need to
 * configure the currency in which you are selling products with.
 */
$currency = new \BTCPayServer\Currency();
$currency->setCode('USD');

// Set the invoice currency
$invoice->setCurrency($currency);

/**
 * To load up keys that you have previously saved, you need to use the same
 * storage engine. You also need to tell it the location of the key you want
 * to load.
 */
$storageEngine = new \BTCPayServer\Storage\FilesystemStorage();
$privateKey    = $storageEngine->load('/tmp/private.key');
$publicKey     = $storageEngine->load('/tmp/public.key');

/**
 * Create a new client.
 */
$btcpay = new \BTCPayServer\BTCPayServer();

/**
 * Create the client that will be used to send requests to BTCPayServer's API
 */
$client = $btcpay->get('client');

$client->setPrivateKey($privateKey);
$client->setPublicKey($publicKey);
/**
 * Add your btcpayserver url
 */
$client->setUri('https://btcpay.server/');

/**
 * You will need to set the token that was returned when you paired your
 * keys.
 */
$token = new \BTCPayServer\Token();
$token->setToken('your token here');

$client->setToken($token);

// Send invoice
$client->createInvoice($invoice);

var_dump(
    (string) $client->getRequest(),
    (string) $client->getResponse(),
    $invoice
);
