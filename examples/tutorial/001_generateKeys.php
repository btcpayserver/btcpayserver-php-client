<?php
/**
 * Copyright (c) 2014-2015 BTCPayServer
 *
 * 001 - Generate and Persist Keys
 *
 * Requirements:
 *   - Basic PHP knowledge
 */

// If you have not already done so, please run `composer.phar install`
require __DIR__.'/../../vendor/autoload.php';

/**
 * Start by creating a PrivateKey object
 */
$privateKey = new \BTCPayServer\PrivateKey('/tmp/btcpayserver.pri');

// Generate a random number
$privateKey->generate();

// You can generate a private key with only one line of code like so
$privateKey = \BTCPayServer\PrivateKey::create('/tmp/btcpayserver.pri')->generate();

// NOTE: This has overridden the previous $privateKey variable, although its
//       not an issue in this case since we have not used this key for
//       anything yet.

/**
 * Once we have a private key, a public key is created from it.
 */
$publicKey = new \BTCPayServer\PublicKey('/tmp/btcpayserver.pub');

// Inject the private key into the public key
$publicKey->setPrivateKey($privateKey);

// Generate the public key
$publicKey->generate();

// NOTE: You can again do all of this with one line of code like so:
//       `$publicKey = \BTCPayServer\PublicKey::create('/tmp/btcpayserver.pub')->setPrivateKey($privateKey)->generate();`

/**
 * Now that you have a private and public key generated, you will need to store
 * them somewhere. This optioin is up to you and how you store them is up to
 * you. Please be aware that you MUST store the private key with some type
 * of security. If the private key is comprimised you will need to repeat this
 * process.
 */

/**
 * It's recommended that you use the EncryptedFilesystemStorage engine to persist your
 * keys. You can, of course, create your own as long as it implements the StorageInterface
 */
$storageEngine = new \BTCPayServer\Storage\EncryptedFilesystemStorage('YourTopSecretPassword');
$storageEngine->persist($privateKey);
$storageEngine->persist($publicKey);

/**
 * This is all for the first tutorial, you can run this script from the command
 * line `php examples/tutorial/001.php` This will generate and create two files
 * located at `/tmp/btcpayserver.pri` and `/tmp/btcpayserver.pub`
 */
