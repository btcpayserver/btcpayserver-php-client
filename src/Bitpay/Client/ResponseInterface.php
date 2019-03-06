<?php
/**
 * @license Copyright 2011-2014 BTCPayServer Inc., MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace Bitpay\Client;

/**
 *
 * @package Bitpay
 */
interface ResponseInterface
{
    /**
     * @return string
     */
    public function getBody();

    /**
     * Returns the status code of the response
     *
     * @return integer
     */
    public function getStatusCode();

    /**
     * Returns a $key => $value array of http headers
     *
     * @return array
     */
    public function getHeaders();
}
