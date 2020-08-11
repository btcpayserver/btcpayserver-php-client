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
 * TODO:
 * - Prompt the user for: key location + Encryption key
 */

use BTCPayServer\PrivateKey;
use BTCPayServer\PublicKey;
use BTCPayServer\Storage\EncryptedFilesystemStorage;

// Require composer-generated autoloader
require __DIR__ . '/../../../../../vendor/autoload.php';

define('PRIVATE_KEY_NAME', 'btcpay.pri');
define('PUBLIC_KEY_NAME', 'btcpay.pub');

// Config, to come from user input
$config = [
    'keydir' => '/tmp/',
    'enckey' => 'YourTopSecretPassword',
];

// Start by creating a PrivateKey object
$privateKey = PrivateKey::create($config['keydir'] . PRIVATE_KEY_NAME)->generate();

// Once we have a private key, a public key is created from it.
$publicKey = new PublicKey($config['keydir'] . PUBLIC_KEY_NAME);

// Inject the private key into the public key
$publicKey->setPrivateKey($privateKey);

// Generate the public key
$publicKey->generate();

/**
 * Now that you have a private and public key generated, you will need to store
 * them somewhere. This optioin is up to you and how you store them is up to
 * you. Please be aware that you MUST store the private key with some type
 * of security. If the private key is compromised you will need to repeat this
 * process.
 *
 * It's recommended that you use the `EncryptedFilesystemStorage` engine to persist your
 * keys. You can, of course, create your own as long as it implements the `StorageInterface`
 */
$storageEngine = new EncryptedFilesystemStorage($config['enckey']);
$storageEngine->persist($privateKey);
$storageEngine->persist($publicKey);

/**
 * This is all for the first tutorial, you can run this script from the command
 * line `php vendor/btcpayserver/btcpayserer-php-client/examples/tutorial/001.php` This will generate and create two files
 * located at `/tmp/btcpay.pri` and `/tmp/btcpay.pub`
 */
