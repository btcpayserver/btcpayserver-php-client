<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer\Storage;

/**
 * @package Bitcore
 */
interface StorageInterface
{
    /**
     * @param KeyInterface $key
     */
    public function persist(\BTCPayServer\KeyInterface $key);

    /**
     * @param string $id
     *
     * @return KeyInterface
     */
    public function load($id);
}
