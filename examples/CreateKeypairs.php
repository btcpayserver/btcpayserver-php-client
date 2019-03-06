<?php
/**
 * Copyright (c) 2014-2015 BTCPayServer
 */

require __DIR__ . '/../vendor/autoload.php';

/**
 * The first argument can either be the path to the key or can be
 * some other unique value. This is a basic example, and more advanced
 * examples can be used to store keys in the database or other places. In
 * this example, however, the keys are not persisted on disk or in a database.
 */
$private = new \BTCPayServer\PrivateKey('/tmp/private.key');
$public  = new \BTCPayServer\PublicKey('/tmp/public.key');
$sin     = new \BTCPayServer\SinKey('/tmp/sin.key');

// Generate Private Key values
$private->generate();

// Generate Public Key values
$public->setPrivateKey($private);
$public->generate();

// Generate Sin Key values
$sin->setPublicKey($public);
$sin->generate();

printf("Public Key:  %s\n", $public);
printf("Private Key: %s\n", $private);
printf("Sin Key:     %s\n\n", $sin);

$manager = new \BTCPayServer\KeyManager(new \BTCPayServer\Storage\EncryptedFilesystemStorage('dN$8WNaT}j<gD3*q'));

$manager->persist($private);
$manager->persist($public);
