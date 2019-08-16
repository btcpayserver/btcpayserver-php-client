<?php
/**
 * Copyright (c) 2014-2015 BitPay
 */

/**
 * WARNING - This example will NOT work until you have generated your public
 * and private keys. Please see the example documentation on generating your
 * keys and also see the documentation on how to save those keys.
 *
 * Also please be aware that you CANNOT create an invoice until you have paired
 * the keys and received a token back. The token is usesd with the request.
 */

require __DIR__ . '/../vendor/autoload.php';


/**
 * Create a new client. You can see the example of how to configure this using
 * a yml file as well.
 */
$btcpay = new \BTCPayServer\BTCPayServer(array(
        'btcpay' => array(
            'public_key' => '/tmp/btcpayserver.pub',
            //see tutorial/001.php and 002.php
            'private_key' => '/tmp/btcpayserver.pri',
            'key_storage' => 'BTCPayServer\Storage\EncryptedFilesystemStorage',
            'key_storage_password' => 'YourTopSecretPassword'
        )
    ));

/**
 * Create the client that will be used to send requests to BitPay's API
 */
$client = $btcpay->get('client');

$tokens = $client->getTokens();
print_r($tokens);
