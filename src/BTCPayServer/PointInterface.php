<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer;

/**
 * @package Bitcore
 */
interface PointInterface extends \Serializable
{
    /**
     * Infinity constant
     *
     * @var string
     */
    const INFINITY = 'inf';

    /**
     * @return string
     */
    public function getX();

    /**
     * @return string
     */
    public function getY();

    /**
     * @return boolean
     */
    public function isInfinity();
}
