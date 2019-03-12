<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer;

/**
 * @package BTCPayServer
 */
interface TokenInterface
{
    /**
     * @return string
     */
    public function getToken();

    /**
     * @return string
     */
    public function getResource();

    /**
     * @return string
     */
    public function getFacade();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return array
     */
    public function getPolicies();

    /**
     * @return string
     */
    public function getPairingCode();

    /**
     * @return \DateTime
     */
    public function getPairingExpiration();
}
