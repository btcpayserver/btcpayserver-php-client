<?php
/**
 * Copyright (c) 2014-2015 BTCPayServer
 */

require __DIR__ . '/../vendor/autoload.php';

/**
 * You are able to keep the configuration values in a YML file which provides
 * you the ability make some easy to use configuration files. You just need
 * to pass in the path to the yml file.
 */
$btcpay = new \BTCPayServer\BTCPayServer(__DIR__ . '/config.yml');
