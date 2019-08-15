<?php
/**
 * Copyright (c) 2014-2015 BitPay
 */

require __DIR__ . '/../vendor/autoload.php';

$client = new \BTCPayServer\Client\Client();
$client->setAdapter(new \BTCPayServer\Client\Adapter\CurlAdapter());
$request = new \BTCPayServer\Client\Request();
$request->setHost('test.btcpayserver.com');
$request->setMethod(\BTCPayServer\Client\Request::METHOD_GET);
$request->setPath('rates/USD');

$response = $client->sendRequest($request);
$data = json_decode($response->getBody(), true);
var_dump($data);



