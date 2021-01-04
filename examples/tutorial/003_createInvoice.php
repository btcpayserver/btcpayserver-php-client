<?php

/**
 * 003 - Creating Invoices
 *
 * Requirements:
 *   - Basic PHP Knowledge
 *   - Private and Public keys from 001_generateKeys.php
 *   - Your "TopSecretPassword" which you used in 001_generateKeys.php
 *   - Account on a BTCPay Server
 *   - Token value obtained from 002_pairing.php
 */

use BTCPayServer\PrivateKey;
use BTCPayServer\PublicKey;
use BTCPayServer\Storage\EncryptedFilesystemStorage;
use BTCPayServer\Client\Client;
use BTCPayServer\Client\Adapter\CurlAdapter;
use BTCPayServer\Token;
use BTCPayServer\Invoice;
use BTCPayServer\Buyer;
use BTCPayServer\Item;
use BTCPayServer\Currency;

require __DIR__ . '/../../vendor/autoload.php';

define('KEY_DIR', __DIR__ . '/tmp'); // directory to store your key files
define('PRIVATE_KEY_NAME', '/btcpay.pri');
define('PUBLIC_KEY_NAME', '/btcpay.pub');
define('SIN_NAME', '/sin.key');
define('PASSWORD', 'TopSecretPassword'); // change this to a strong password
define('SERVER_URL', 'https://yourserver.domain.com'); // change to your server (no trailing slash)
define('SERVER_PORT', '443'); // change to your server port
define('PAIRING_CODE', '<PairingToken>'); // pairing code which you get in the admin panel of your btcpay server
define('PAIRING_LABEL', 'PairingToken'); // change to whatever you want
define('TOKEN', '<ApiToken>'); // change to you token received in 002_pairing.php
define('IPN_CALLBACK', 'https://yourServer.com/ipn_callback.php');

$storageEngine = new EncryptedFilesystemStorage(PASSWORD);
$privateKey = $storageEngine->load(KEY_DIR . PRIVATE_KEY_NAME);
$publicKey = $storageEngine->load(KEY_DIR . PUBLIC_KEY_NAME);

$client = new Client();
$adapter = new CurlAdapter();

$client->setPrivateKey($privateKey);
$client->setPublicKey($publicKey);
$client->setUri(SERVER_URL . ':' . SERVER_PORT);
$client->setAdapter($adapter);
// ---------------------------

/**
 * The last object that must be injected is the token object.
 */
$token = new Token();
$token->setToken(TOKEN);

/**
 * Token object is injected into the client
 */
$client->setToken($token);

/**
 * This is where we will start to create an Invoice object, make sure to check
 * the InvoiceInterface for methods that you can use.
 */
$invoice = new Invoice();

$buyer = new Buyer();
$buyer->setEmail('buyeremail@test.com');

// Add the buyers info to invoice
$invoice->setBuyer($buyer);

/**
 * Item is used to keep track of a few things
 */
$item = new Item();
$item->setCode('skuNumber')->setDescription('General Description of Item')->setPrice('1.99');
$invoice->setItem($item);

/**
 * BitPay supports multiple different currencies. Most shopping cart applications
 * and applications in general have defined set of currencies that can be used.
 * Setting this to one of the supported currencies will create an invoice using
 * the exchange rate for that currency.
 *
 * @see https://test.bitpay.com/bitcoin-exchange-rates for supported currencies
 */
$invoice->setCurrency(new Currency('USD'));

// Configure the rest of the invoice
$invoice->setOrderId('OrderIdFromYourSystem');

// You will receive IPN's at this URL, should be HTTPS for security purposes!
$invoice->setNotificationUrl(IPN_CALLBACK);

/**
 * Updates invoice with new information such as the invoice id and the URL where
 * a customer can view the invoice.
 */
try {
    echo "Creating invoice at your BTCPay Server now." . PHP_EOL;
    $client->createInvoice($invoice);
} catch (\Exception $e) {
    echo "Exception occured: " . $e->getMessage() . PHP_EOL;
    $request = $client->getRequest();
    $response = $client->getResponse();
    echo (string)$request . PHP_EOL . PHP_EOL . PHP_EOL;
    echo (string)$response . PHP_EOL . PHP_EOL;
    exit(1); // We do not want to continue if something went wrong
}

echo 'Invoice "' . $invoice->getId() . '" created, see ' . $invoice->getUrl() . PHP_EOL;
echo "Verbose details." . PHP_EOL;
print_r($invoice);
