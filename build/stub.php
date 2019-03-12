<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

require_once 'phar://btcpayserver.phar/src/BTCPayServer/Autoloader.php';
\BTCPayServer\Autoloader::register();
require_once 'phar://btcpayserver.phar/bin/btcpayserver';
__HALT_COMPILER();
