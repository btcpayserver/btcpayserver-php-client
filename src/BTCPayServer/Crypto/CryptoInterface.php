<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer\Crypto;

/**
 * All crypto extensions MUST support this interface
 */
interface CryptoInterface
{
    /**
     * If the users system supports the cryto extension, this should return
     * true, otherwise it should return false.
     *
     * @return boolean
     */
    public static function hasSupport();

    /**
     * @return array
     */
    public function getAlgos();
}
