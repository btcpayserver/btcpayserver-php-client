<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer;

/**
 * @package Bitcore
 */
interface KeyInterface extends \Serializable
{
    /**
     * Generates a new key
     */
    public function generate();

    /**
     * @return boolean
     */
    public function isValid();
}
