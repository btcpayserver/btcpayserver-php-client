<?php
/**
 * @license Copyright 2011-2014 BTCPayServer Inc., MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace Bitpay;

/**
 * Creates an application for a new merchant account
 *
 * @package Bitpay
 */
interface ApplicationInterface
{
    /**
     * @return array
     */
    public function getUsers();

    /**
     * @return array
     */
    public function getOrgs();
}
