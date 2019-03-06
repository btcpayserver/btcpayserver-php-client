<?php

function generateAndPersistKeys()
{
    $privateKey = new \BTCPayServer\PrivateKey('/tmp/btcpayserver.pri');
    $privateKey->generate();
    $publicKey = new \BTCPayServer\PublicKey('/tmp/btcpayserver.pub');
    $publicKey->setPrivateKey($privateKey);
    $publicKey->generate();
    $sinKey = new \BTCPayServer\SinKey('/tmp/sin.key');
    $sinKey->setPublicKey($publicKey);
    $sinKey->generate();

    //Persist Keys
    $storageEngine = new \BTCPayServer\Storage\EncryptedFilesystemStorage('YourTopSecretPassword');
    $storageEngine->persist($privateKey);
    $storageEngine->persist($publicKey);

    return array($privateKey, $publicKey, $sinKey);
}

function loadKeys()
{
    $storageEngine = new \BTCPayServer\Storage\EncryptedFilesystemStorage('YourTopSecretPassword');
    $privateKey    = $storageEngine->load('/tmp/btcpayserver.pri');
    $publicKey     = $storageEngine->load('/tmp/btcpayserver.pub');
    $token_id      = file_get_contents('/tmp/token.json');

    return array($privateKey, $publicKey, $token_id);
}

function createClient($network, $privateKey = null, $publicKey = null, $curl_options = null)
{
    if(true === is_null($curl_options)) {
        $curl_options = array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );
    }
    $adapter = new \BTCPayServer\Client\Adapter\CurlAdapter($curl_options);
    $client = new \BTCPayServer\Client\Client();

    if(true === !is_null($privateKey)) {
        $client->setPrivateKey($privateKey);
    }
    if(true === !is_null($publicKey)) {
        $client->setPublicKey($publicKey);
    }

    $client->setNetwork($network);
    $client->setAdapter($adapter);

    return $client;
}