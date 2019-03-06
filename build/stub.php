<?php
/**
 * @license Copyright 2011-2015 BTCPayServer Inc., MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

require_once 'phar://bitpay.phar/src/Bitpay/Autoloader.php';
\Bitpay\Autoloader::register();
require_once 'phar://bitpay.phar/bin/bitpay';
__HALT_COMPILER();
