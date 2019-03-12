<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer;

/**
 * Creates an application for a new merchant account
 *
 * @package BTCPayServer
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
