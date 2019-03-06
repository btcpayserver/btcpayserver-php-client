<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace BTCPayServer;

class AutoloaderTest extends \PHPUnit_Framework_TestCase
{

    protected function teardown()
    {
        Autoloader::unregister();
    }

    /**
     * Make sure that our autoloader is first in the queue
     */
    public function testRegister()
    {
        Autoloader::register();
        $functions = spl_autoload_functions();
        $this->assertSame(array('BTCPayServer\Autoloader','autoload'), $functions[0]);
    }

    public function testUnregister()
    {
        Autoloader::register();
        $numOfAutoloaders = count(spl_autoload_functions());
        Autoloader::unregister();
        $this->assertCount($numOfAutoloaders - 1, spl_autoload_functions());
    }

    public function testAutoload()
    {
        Autoloader::register();

        Autoloader::autoload('BTCPayServer\BTCPayServer');
        // Is only required once
        Autoloader::autoload('BTCPayServer\BTCPayServer');
    }

    /**
     */
    public function testNoClass()
    {
        Autoloader::autoload('Foo\Bar');
    }

    /**
     * @expectedException Exception
     */
    public function testException()
    {
        Autoloader::autoload('BTCPayServer\ClassThatWillNeverBeCreated');
    }

    public function testNoExceptionForBTCPayServerClasslike()
    {
        Autoloader::register();

        // Magento Classes
        Autoloader::autoload('BTCPayServer_Core_Model');
    }
}
