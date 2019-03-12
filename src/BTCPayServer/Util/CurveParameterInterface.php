<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer\Util;

/**
 */
interface CurveParameterInterface
{
    public function aHex();
    public function bHex();
    public function gHex();
    public function gxHex();
    public function gyHex();
    public function hHex();
    public function nHex();
    public function pHex();
}
