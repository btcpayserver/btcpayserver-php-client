<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer;

/**
 * Class PayoutTransaction
 * @package BTCPayServer
 */
interface PayoutTransactionInterface
{
    /**
     * Get bitcoin blockchain transaction ID for the payout transaction.
     * @return mixed
     */
    public function getTransactionID();

    /**
     * The amount of bitcoin paid.
     * @return float
     */
    public function getAmount();

    /**
     * The date and time when the payment was sent.
     * @return string
     */
    public function getDate();
}
