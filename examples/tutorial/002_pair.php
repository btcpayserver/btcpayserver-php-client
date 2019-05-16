<?php
/**
 * Copyright (c) 2014-2015 BTCPayServer
 *
 * 002 - Pairing
 *
 * Requirements:
 *   - Basic PHP Knowledge
 *   - Private and Public keys from 001.php
 *   - Account on https://testnet.demo.btcpayserver.org/
 *   - Pairing code
 */
require __DIR__.'/../../vendor/autoload.php';

/**
 * To load up keys that you have previously saved, you need to use the same
 * storage engine. You also need to tell it the location of the key you want
 * to load.
 */
$storageEngine = new \BTCPayServer\Storage\EncryptedFilesystemStorage('YourTopSecretPassword');
$privateKey    = $storageEngine->load('/tmp/btcpayserver.pri');
$publicKey     = $storageEngine->load('/tmp/btcpayserver.pub');

/**
 * Create the client, there's a lot to it and there are some easier ways, I am
 * showing the long form here to show how various things are injected into the
 * client.
 */
$client = new \BTCPayServer\Client\Client();

/**
 * The adapter is what will make the calls to BTCPayServer and return the response
 * from BTCPayServer. This can be updated or changed as long as it implements the
 * AdapterInterface
 */
$adapter = new \BTCPayServer\Client\Adapter\CurlAdapter();

/**
 * Now all the objects are created and we can inject them into the client
 */
$client->setPrivateKey($privateKey);
$client->setPublicKey($publicKey);

/**
 * Add your btcpayserver url
 */
$client->setUri('https://btcpay.server/');

$client->setAdapter($adapter);

/**
 * Visit https://testnet.demo.btcpayserver.org/api-tokens and create a new pairing code. Pairing
 * codes can only be used once and the generated code is valid for only 24 hours.
 */
$pairingCode = 'InsertPairingCodeHere';

/**
 * Currently this part is required, however future versions of the PHP SDK will
 * be refactor and this part may become obsolete.
 */
$sin = \BTCPayServer\SinKey::create()->setPublicKey($publicKey)->generate();
/**** end ****/

try {
    $token = $client->createToken(
        array(
            'pairingCode' => $pairingCode,
            'label'       => 'You can insert a label here',
            'id'          => (string) $sin,
        )
    );
} catch (\Exception $e) {
    /**
     * The code will throw an exception if anything goes wrong, if you did not
     * change the $pairingCode value or if you are trying to use a pairing
     * code that has already been used, you will get an exception. It was
     * decided that it makes more sense to allow your application to handle
     * this exception since each app is different and has different requirements.
     */
    echo "Exception occured: " . $e->getMessage().PHP_EOL;

    echo "Pairing failed. Please check whether you're trying to pair a production pairing code on test.".PHP_EOL;
    $request  = $client->getRequest();
    $response = $client->getResponse();
    /**
     * You can use the entire request/response to help figure out what went
     * wrong, but for right now, we will just var_dump them.
     */
    echo (string) $request.PHP_EOL.PHP_EOL.PHP_EOL;
    echo (string) $response.PHP_EOL.PHP_EOL;
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
echo 'Token obtained: '.$persistThisValue.PHP_EOL;

/**
 * Make sure you persist the token, you will need it for the next tutorial
 */
