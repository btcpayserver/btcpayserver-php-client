<?php
/**
 * @license Copyright 2011-2015 BTCPayServer Inc., MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace Bitpay;

/**
 * Creates an access token for the given client
 *
 * @package Bitpay
 */
interface AccessTokenInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return boolean
     */
    public function isNonceDisabled();
}
