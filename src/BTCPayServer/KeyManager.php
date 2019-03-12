<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer;

/**
 * Used to manage keys
 *
 * @package Bitcore
 */
class KeyManager
{
    /**
     * @var BTCPayServer\Storage\StorageInterface
     */
    protected $storage;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(\BTCPayServer\Storage\StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param KeyInterface $key
     */
    public function persist(KeyInterface $key)
    {
        $this->storage->persist($key);
    }

    /**
     * @return KeyInterface
     */
    public function load($id)
    {
        return $this->storage->load($id);
    }
}
