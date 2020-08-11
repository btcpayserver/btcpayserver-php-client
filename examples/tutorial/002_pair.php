<?php
/**
 * This script needs to be run only once per BTCPayServer setup. If you need to onnect your web-store
 * to a completely new BTCPayServer instance, this script will need to be run again.
 *
 * What does it do?
 * 
 * 1. Generates a private and public key, encrypts them and saves them to the filesystem on which it was run
 * 2.  
 *
 * What you need:
 *
 * - The Private and Public keys generated from 001_generateKeys.php
 * - An account with an instance of btcpayserver
 * - A "Pairing code" generated from within yur instance of BTCPayServer
 *
 * TODO:
 * - Prompt the user for: key location + Encryption key
 */

use BTCPayServer\Storage\EncryptedFilesystemStorage;
use BTCPayServer\Client\Client;
use BTCPayServer\Client\Adapter\CurlAdapter;
use BTCPayServer\SinKey;

// Require composer-generated autoloader
require __DIR__ . '/../../../../../vendor/autoload.php';

define('PRIVATE_KEY_NAME', 'btcpay.pri');
define('PUBLIC_KEY_NAME', 'btcpay.pub');

// Config, to come from user input
$config = [
    'keydir' => '/tmp/',
    'enckey' => 'YourTopSecretPassword',
    'btcpay_host' => 'https://testnet.demo.btcpayserver.org:443',
    'pairing_code' => '8nmCvLR',
    // An integration with BTCPay server is typically the "merchant" facade.
    'facade' => 'merchant',
];

/**
 * To load up keys that you have previously saved, you need to use the same
 * storage engine. You also need to tell it the location of the key you want
 * to load.
 */
$storageEngine = new EncryptedFilesystemStorage($config['enckey']);
$privateKey = $storageEngine->load($config['keydir'] . PRIVATE_KEY_NAME);
$publicKey = $storageEngine->load($config['keydir'] . PUBLIC_KEY_NAME);

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
$client->setUri($config['btcpay_host']);
$client->setAdapter($adapter);

/**
 * Visit your BTCPayServer instance, login and within it, create a new pairing code.
 * Pairing codes can only be used once and the generated code is valid for only 24 hours.
 */
$pairingCode = $config['pairing_code'];

/**
 * Currently this part is required, however future versions of the PHP SDK will
 * be refactor and this part may become obsolete.
 */
$sin = new SinKey('/tmp/sin.key');
$sin->setPublicKey($publicKey);
$sin->generate();

error_log('$pairingCode: ' . $pairingCode);
error_log('$sin: ' . $sin);

// 'empty' will be POS
// payroll
// merchant

if (!$config['facade']):
    try {
        $token = $client->createToken([
                'pairingCode' => $pairingCode,
                'label' => 'description',
                'id' => (string)$sin,
        ]);
    } catch (\Exception $e) {
        /**
         * The code will throw an exception if anything goes wrong, if you did not
         * change the $pairingCode value or if you are trying to use a pairing
         * code that has already been used, you will get an exception. It was
         * decided that it makes more sense to allow your application to handle
         * this exception itself, since each app is different and will of course
         * have differing requirements.
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
     * your own way to persist data. Such as using a framework or some other framework
     * such as Laravel, Silverstripe or Drupal.
     */
    $persistThisValue = $token->getToken();
    echo 'Token obtained: ' . $persistThisValue . PHP_EOL;
endif;

if ($config['facade'] == 'merchant' || $config['facade'] == 'payroll'):
    try {
        $token = $client->createToken([
            'facade' => $config['facade'],
            'label' => 'label this token',
            'id' => (string)$sin,
        ]);

        echo "<pre>";
        var_dump($token);
        echo "</pre>";

        $pairingCode = $token->GetpairingCode();
        $url = $config['btcpay_url'] . '/api-access-request?pairingCode=' . $pairingCode;

        echo "\n$url\n";
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
         * user friendly string.
         */
        exit(1); // We do not want to continue if something went wrong
    }
endif;

/**
 * Note: Make sure you persist the token, you will need it for the next tutorial
 */
