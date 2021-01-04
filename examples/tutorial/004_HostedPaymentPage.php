<?php
/**
 * 004 - Hosted payment page: create & display invoice
 * For details on displaying invoices, see https://bitpay.com/docs/display-invoice
 *
 * Requirements:
 *   - Basic PHP Knowledge
 *   - Private and Public keys from 001_generateKeys.php
 *   - Your "TopSecretPassword" which you used in 001_generateKeys.php
 *   - Account on a BTCPay Server
 *   - Token value obtained from 002_pairing.php
 *   - A webserver to run the code. Running locally works with firefox, but not with Safari & Chrome
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

$token = new Token();
$token->setToken(TOKEN);
$client->setToken($token);

/**
 * For more information about creating a invoice, please check 003_createInvoice.php
 * The way we create the invoice here is the same as in the example before.
 */
$invoice = new \BTCPayServer\Invoice();
$buyer = new \BTCPayServer\Buyer();
$buyerEmail = "buyeremail@test.com";
$buyer->setEmail($buyerEmail);
$invoice->setBuyer($buyer);
$item = new \BTCPayServer\Item();
$item->setCode('skuNumber')->setDescription('General Description of Item')->setPrice('1.99');
$invoice->setItem($item);
$invoice->setCurrency(new \BTCPayServer\Currency('USD'));
$invoice->setOrderId('OrderIdFromYourSystem');
$invoice->setNotificationUrl(IPN_CALLBACK);

try {
    $client->createInvoice($invoice);
} catch (\Exception $e) {
    $request = $client->getRequest();
    $response = $client->getResponse();
    echo (string)$request . PHP_EOL . PHP_EOL . PHP_EOL;
    echo (string)$response . PHP_EOL . PHP_EOL;
    exit(1); // We do not want to continue if something went wrong
}
?>

<html>
<head><title>BTCPay Server Modal Checkout</title></head>
<body>
    <button onclick="openInvoice()">Pay Now</button>
    <br><br><br>
    For more information about BTCPay Server´s modal checkout, please see 
    <a href="https://docs.btcpayserver.org/CustomIntegration/#modal-checkout" target="_blank">
        BTCPay Server Doc -> Custom Integration -> Modal Checkout
    </a>
</body>
<script src="<?= SERVER_URL ?>/modal/btcpay.js"></script>
<script>
    function openInvoice() {
        bitpay.setApiUrlPrefix(<?= SERVER_URL ?>)
        bitpay.showInvoice("<?php echo $invoice->getId();?>");
    }
</script>
</html>
