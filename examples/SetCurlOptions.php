<?php
/**
 * Copyright (c) 2014-2015 BTCPayServer
 */

require __DIR__ . '/../vendor/autoload.php';

// Customize the curl options
$curl_options = array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            );

// If nothing is passed into the CurlAdapter
// then default values are used
$adapter = new BTCPayServer\Client\Adapter\CurlAdapter($curl_options);
