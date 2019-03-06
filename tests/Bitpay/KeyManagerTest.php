<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace BTCPayServer;

class KeyManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $storage = $this->getMockStorage();
        $this->assertNotNull($storage);

        $manager = new KeyManager($storage);
        $this->assertNotNull($manager);
    }

    /**
     * @depends testConstruct
     */
    public function testPersist()
    {
        $storage = $this->getMockStorage();
        $this->assertNotNull($storage);

        $manager = new KeyManager($storage);
        $this->assertNotNull($manager);

        $manager->persist($this->getMockKey());
    }

    /**
     * @depends testConstruct
     */
    public function testLoad()
    {
        $storage = $this->getMockStorage();
        $this->assertNotNull($storage);

        $manager = new KeyManager($storage);
        $this->assertNotNull($manager);

        $manager->load($this->getMockKey());
    }

    private function getMockKey()
    {
        return new \BTCPayServer\PublicKey('/tmp/mock.key');
    }

    private function getMockStorage()
    {
        return new \BTCPayServer\Storage\MockStorage();
    }
}
