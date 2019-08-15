<?php
namespace BTCPayServer\Network;

/**
 *
 * @package Bitcore
 */
interface NetworkInterface
{
    /**
     * Name of network, currently on livenet and testnet
     *
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getAddressVersion();

    /**
     * The host that is used to interact with this network
     * @return string
     */
    public function getApiHost();

    /**
     * The port of the host
     *
     * @return integer
     */
    public function getApiPort();
}
