<?php
/**
 * Copyright (c) 2014-2015 BTCPayServer
 *
 * 003 - Creating Invoices
 *
 * Requirements:
 *   - Account on https://testnet.demo.btcpayserver.org
 *   - Basic PHP Knowledge
 *   - Private and Public keys from 001.php
 *   - Token value obtained from 002.php
 */
require __DIR__.'/../../vendor/autoload.php';

// See 002.php for explanation
$storageEngine = new \BTCPayServer\Storage\EncryptedFilesystemStorage('YourTopSecretPassword'); // Password may need to be updated if you changed it
$privateKey    = $storageEngine->load('/tmp/btcpayserver.pri');
$publicKey     = $storageEngine->load('/tmp/btcpayserver.pub');
$client        = new \BTCPayServer\Client\Client();
$adapter       = new \BTCPayServer\Client\Adapter\CurlAdapter();
$client->setPrivateKey($privateKey);
$client->setPublicKey($publicKey);
$client->setUri('https://testnet.demo.btcpayserver.org/');
$client->setAdapter($adapter);
// ---------------------------

/**
 * The last object that must be injected is the token object.
 */
$token = new \BTCPayServer\Token();
$token->setToken('UpdateThisValue'); // UPDATE THIS VALUE

/**
 * Token object is injected into the client
 */
$client->setToken($token);

/**
 * This is where we will start to create an Invoice object, make sure to check
 * the InvoiceInterface for methods that you can use.
 */
$invoice = new \BTCPayServer\Invoice();

$buyer = new \BTCPayServer\Buyer();
$buyer
    ->setEmail('buyeremail@test.com');

// Add the buyers info to invoice
$invoice->setBuyer($buyer);

/**
 * Item is used to keep track of a few things
 */
$item = new \BTCPayServer\Item();
$item
    ->setCode('skuNumber')
    ->setDescription('General Description of Item')
    ->setPrice('1.99');
$invoice->setItem($item);

/**
 * BTCPayServer supports multiple different currencies. Most shopping cart applications
 * and applications in general have defined set of currencies that can be used.
 * Setting this to one of the supported currencies will create an invoice using
 * the exchange rate for that currency.
 *
 * @see https://docs.btcpayserver.org/faq-and-common-issues/faq-general#which-cryptocurrencies-are-supported-in-btcpay for supported currencies
 */
$invoice->setCurrency(new \BTCPayServer\Currency('USD'));

// Configure the rest of the invoice
$invoice
    ->setOrderId('OrderIdFromYourSystem')
    // You will receive IPN's at this URL, should be HTTPS for security purposes!
    ->setNotificationUrl('https://store.example.com/btcpayserver/callback');


/**
 * Updates invoice with new information such as the invoice id and the URL where
 * a customer can view the invoice.
 */
try {
    echo "Creating invoice at BTCPayServer now.".PHP_EOL;
    $client->createInvoice($invoice);
} catch (\Exception $e) {
    echo "Exception occured: " . $e->getMessage().PHP_EOL;
    $request  = $client->getRequest();
    $response = $client->getResponse();
    echo (string) $request.PHP_EOL.PHP_EOL.PHP_EOL;
    echo (string) $response.PHP_EOL.PHP_EOL;
    exit(1); // We do not want to continue if something went wrong
}

echo 'Invoice "'.$invoice->getId().'" created, see '.$invoice->getUrl().PHP_EOL;
echo "Verbose details.".PHP_EOL;
print_r($invoice);
