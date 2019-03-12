<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer\Storage;

/**
 * @codeCoverageIgnore
 * @package Bitcore
 */
class MockStorage implements StorageInterface
{
    public function persist(\BTCPayServer\KeyInterface $key)
    {
    }

    public function load($id)
    {
        return;
    }
}
