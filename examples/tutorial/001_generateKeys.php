<?php

/**
 * 001 - Generate keys
 * 
 * This script needs to be run only once per BTCPayServer setup. 
 * For every next request which you´ll send to the BTCPay Server
 * the request as to be complemented with these keys.
 *
 * Requirements:
 *   - Basic PHP Knowledge
 *   - if you have not already done so, please run `composer.phar install`
 */

use BTCPayServer\PrivateKey;
use BTCPayServer\PublicKey;
use BTCPayServer\Storage\EncryptedFilesystemStorage;

require __DIR__ . '/../../vendor/autoload.php';

define('KEY_DIR', __DIR__ . '/tmp'); // directory to store your key files
define('PRIVATE_KEY_NAME', '/btcpay.pri');
define('PUBLIC_KEY_NAME', '/btcpay.pub');
define('PASSWORD', 'TopSecretPassword'); // change this to a strong password

// Start by creating a PrivateKey object
$privateKey = PrivateKey::create(KEY_DIR . PRIVATE_KEY_NAME)->generate();

// then create a PublicKey Object
$publicKey = new PublicKey(KEY_DIR . PUBLIC_KEY_NAME);

// inject the private key into the public key
$publicKey->setPrivateKey($privateKey);

// generate the public key
$publicKey->generate();

/**
 * NOTE: You can again do all of this with one line of code like so:
 * $publicKey = \BTCPayServer\PublicKey::create(KEY_DIR . PUBLIC_KEY_NAME)->setPrivateKey($privateKey)->generate();
 */ 

/**
 * Now that you have a private and public key generated, you will need to store
 * them somewhere. This option is up to you and how you store them is up to
 * you. Please be aware that you MUST store the private key with some type
 * of security. If the private key is compromised you will need to repeat this
 * process.
 */

/**
 * It's recommended that you use the EncryptedFilesystemStorage engine to persist your
 * keys. You can, of course, create your own as long as it implements the StorageInterface
 */
$storageEngine = new EncryptedFilesystemStorage(PASSWORD);
$storageEngine->persist($privateKey);
$storageEngine->persist($publicKey);

/**
 * This is all for the first tutorial, you can run this script from the command
 * line `php examples/tutorial/001.php` This will generate and create two files
 * located at `/tmp/btcpay.pri` and `/tmp/btcpay.pub`
 */