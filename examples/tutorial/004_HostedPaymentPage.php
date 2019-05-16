<?php
/**
 * Copyright (c) 2014-2017 BTCPayServer
 *
 * 004 - Hosted payment page: create & display invoice
 * For details on displaying invoices, see https://docs.btcpayserver.org/integrations/customintegration#modal-checkout
 * Requirements:
 *   - Account on https://testnet.demo.btcpayserver.org
 *   - Basic PHP Knowledge
 *   - Private and Public keys from 001.php
 *   - Token value obtained from 002.php
 *   - A webserver to run the code. Running locally works with firefox, but not with Safari & Chrome
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
$client->setUri('https://btcpay.server/');
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
$buyerEmail = "buyeremail@test.com";
$buyer
    ->setEmail($buyerEmail);

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
    $client->createInvoice($invoice);
} catch (\Exception $e) {
    $request  = $client->getRequest();
    $response = $client->getResponse();
    echo (string) $request.PHP_EOL.PHP_EOL.PHP_EOL;
    echo (string) $response.PHP_EOL.PHP_EOL;
    exit(1); // We do not want to continue if something went wrong
}
?>
<html>
  <head><title>BTCPayServer - Modal CSS invoice demo</title></head>
  <body bgcolor="rgb(21,28,111)" textcolor="rgb(255,255,255)">
    <button onclick="openInvoice()">Pay Now</button>
    <br><br><br>
    For more information about BTCPayServer's modal CSS invoice, please see <a href="https://docs.btcpayserver.org/integrations/customintegration#modal-checkout" target="_blank">https://btcpayserver.com/docs/display-invoice</a>
  </body>
  <script src="https://testnet.demo.btcpayserver.org/modal/btcpay.js"> </script>
  <script>
    function openInvoice() {
      var network = "testnet"
      if (network == "testnet")
        btcpayserver.setApiUrlPrefix("https://testnet.demo.btcpayserver.org")
      else
        btcpayserver.setApiUrlPrefix("https://btcpayserver.org/")
      btcpayserver.showInvoice("<?php echo $invoice->getId();?>");
    }
  </script>
</html>
