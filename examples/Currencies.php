<?php
/**
 * Copyright (c) 2014-2015 BitPay
 */

require __DIR__ . '/../vendor/autoload.php';

$btcpay = new \BTCPayServer\BTCPayServer(__DIR__ . '/config.yml');
$client = $btcpay->get('client');
$currencies = $client->getCurrencies();

/** @var \BTCPayServer\Currency $currencies [0] * */
var_dump($currencies[0]);
