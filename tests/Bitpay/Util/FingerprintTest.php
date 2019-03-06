<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace BTCPayServer\Util;

class FingerprintTest extends \PHPUnit_Framework_TestCase
{

    public function testGenerate()
    {
        $finger = Fingerprint::generate();
        $this->assertNotNull($finger);

        // Make sure it generates the same value
        $this->assertSame($finger, Fingerprint::generate());
    }
}
