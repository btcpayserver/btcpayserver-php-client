<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace BTCPayServer;

use org\bovigo\vfs\vfsStream;

class BTCPayServerTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $bitpay = new \BTCPayServer\BTCPayServer(
            array(
                'bitpay' => array()
            )
        );
    }

    public function testGetContainer()
    {
        $bitpay = new \BTCPayServer\BTCPayServer();
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerInterface', $bitpay->getContainer());
    }

    public function testGet()
    {
        $bitpay = new \BTCPayServer\BTCPayServer();
        $this->assertInstanceOf('BTCPayServer\Client\Adapter\CurlAdapter', $bitpay->get('adapter'));
    }

    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function testGetInvalidService()
    {
        $bitpay = new \BTCPayServer\BTCPayServer();
        $bitpay->get('coins');
    }

    public function testConfigAbleToPersistAndLoadKeys()
    {
        $root   = vfsStream::setup('tmp');
        $bitpay = new \BTCPayServer\BTCPayServer(
            array(
                'bitpay' => array(
                    'private_key' => vfsStream::url('tmp/key.pri'),
                    'public_key'  => vfsStream::url('tmp/key.pub'),
                )
            )
        );

        $pri = new \BTCPayServer\PrivateKey(vfsStream::url('tmp/key.pri'));
        $pri->generate();
        $pub = new \BTCPayServer\PublicKey(vfsStream::url('tmp/key.pub'));
        $pub->setPrivateKey($pri)->generate();

        /**
         * Save keys to the filesystem
         */
        $storage = $bitpay->get('key_storage');
        $storage->persist($pri);
        $storage->persist($pub);

        /**
         * This will load the keys, if you have not already persisted them, than
         * this WILL throw an Exception since this will load the keys from the
         * storage class
         */
        $pri = $bitpay->get('private_key');
        $pub = $bitpay->get('public_key');
    }
}
