<?php

/**
 * 002 - Pairing
 * 
 * This script needs to be run only once per BTCPayServer setup. 
 * If you need to connect your web-store to a completely new 
 * BTCPayServer instance, this script will need to be run again.
 *
 * Requirements:
 *   - Basic PHP Knowledge
 *   - Private and Public keys from 001_generateKeys.php
 *   - Your "TopSecretPassword" which you used in 001_generateKeys.php
 *   - Account on a BTCPay Server
 *   - Pairing code
 */

use BTCPayServer\PrivateKey;
use BTCPayServer\PublicKey;
use BTCPayServer\Storage\EncryptedFilesystemStorage;
use BTCPayServer\Client\Client;
use BTCPayServer\Client\Adapter\CurlAdapter;

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

/**
 * To load up keys that you have previously saved, you need to use the same
 * storage engine. You also need to tell it the location of the key you want
 * to load.
 */
$storageEngine = new EncryptedFilesystemStorage(PASSWORD);
$privateKey = $storageEngine->load(KEY_DIR . PRIVATE_KEY_NAME);
$publicKey = $storageEngine->load(KEY_DIR . PUBLIC_KEY_NAME);

/**
 * Create the client, there's a lot to it and there are some easier ways, I am
 * showing the long form here to show how various things are injected into the
 * client.
 */
$client = new Client();

/**
 * The adapter is what will make the calls to BitPay and return the response
 * from BitPay. This can be updated or changed as long as it implements the
 * AdapterInterface
 */
$adapter = new CurlAdapter();

/**
 * Now all the objects are created and we can inject them into the client
 */
$client->setPrivateKey($privateKey);
$client->setPublicKey($publicKey);
$client->setUri(SERVER_URL . ':' . SERVER_PORT);
$client->setAdapter($adapter);

/**
 * Visit https://YOURBTCPAYSERVER.com/api-tokens and create a new pairing code. Pairing
 * codes can only be used once and the generated code is valid for only 24 hours.
 */
$pairingCode = PAIRING_CODE;

/**
 * Currently this part is required, however future versions of the PHP SDK will
 * be refactor and this part may become obsolete.
 */
$sin = new \BTCPayServer\SinKey(KEY_DIR . SIN_NAME);
$sin->setPublicKey($publicKey);
$sin->generate();

$facade = '';
// 'empty' will be POS
// payroll
// merchant

if (!$facade):
    try {
        $token = $client->createToken(array(
                'pairingCode' => $pairingCode,
                'label' => PAIRING_LABEL,
                'id' => (string)$sin,
            ));
    } catch (\Exception $e) {
        /**
         * The code will throw an exception if anything goes wrong, if you did not
         * change the $pairingCode value or if you are trying to use a pairing
         * code that has already been used, you will get an exception. It was
         * decided that it makes more sense to allow your application to handle
         * this exception since each app is different and has different requirements.
         */
        echo "Exception occured: " . $e->getMessage() . PHP_EOL;

        echo "Pairing failed. Please check whether you're trying to pair a production pairing code on test." . PHP_EOL;
        $request = $client->getRequest();
        $response = $client->getResponse();
        /**
         * You can use the entire request/response to help figure out what went
         * wrong, but for right now, we will just var_dump them.
         */
        echo (string)$request . PHP_EOL . PHP_EOL . PHP_EOL;
        echo (string)$response . PHP_EOL . PHP_EOL;
        /**
         * NOTE: The `(string)` is include so that the objects are converted to a
         *       user friendly string.
         */

        exit(1); // We do not want to continue if something went wrong
    }

    /**
     * You will need to persist the token somewhere, by the time you get to this
     * point your application has implemented an ORM such as Doctrine or you have
     * your own way to persist data. Such as using a framework or some other code
     * base such as Drupal.
     */
    $persistThisValue = $token->getToken();
    echo PHP_EOL . 'Token obtained: ' . $persistThisValue . PHP_EOL;
endif;

if ($facade == 'merchant' || $facade == 'payroll'):
    try {
        $token = $client->createToken(array(
                'facade' => $facade,
                'label' => PAIRING_LABEL,
                'id' => (string)$sin,
            ));


        echo "<pre>";
        var_dump($token);
        echo "</pre>";

        $pairingCode = $token->GetpairingCode();

        $url = SERVER_URL . '/api-access-request?pairingCode=' . $pairingCode;

        echo "\n$url";
        echo "\n";
        echo 'Token obtained: ' . $token->getToken() . PHP_EOL;

    } catch (\Exception $e) {
        echo "Exception occured: " . $e->getMessage() . PHP_EOL;

        echo "Pairing failed. Please check whether you're trying to pair a production pairing code on test." . PHP_EOL;
        $request = $client->getRequest();
        $response = $client->getResponse();
        /**
         * You can use the entire request/response to help figure out what went
         * wrong, but for right now, we will just var_dump them.
         */
        echo (string)$request . PHP_EOL . PHP_EOL . PHP_EOL;
        echo (string)$response . PHP_EOL . PHP_EOL;
        /**
         * NOTE: The `(string)` is include so that the objects are converted to a
         *       user friendly string.
         */

        exit(1); // We do not want to continue if something went wrong

    }
endif;

/**
 * Make sure you persist the token, you will need it for the next tutorial !
 */
