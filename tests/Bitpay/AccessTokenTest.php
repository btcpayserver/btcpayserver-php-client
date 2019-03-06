<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace BTCPayServer;

class AccessTokenTest extends \PHPUnit_Framework_TestCase
{
    public function testId()
    {
        $token = new AccessToken();

        $this->assertNotNull($token);

        $token->setId('test');

        $this->assertSame('test', $token->getId());
    }

    public function testEmail()
    {
        $token = new AccessToken();

        $this->assertNotNull($token);

        $token->setEmail('support@btcpayserver.com');

        $this->assertSame('support@btcpayserver.com', $token->getEmail());
    }

    public function testLabel()
    {
        $token = new AccessToken();

        $this->assertNotNull($token);

        $token->setLabel('label');

        $this->assertSame('label', $token->getLabel());
    }

    public function testNonce()
    {
        $token = new AccessToken();

        $this->assertNotNull($token);

        $this->assertFalse($token->isNonceDisabled());
    }

    public function testNonceDisable()
    {
        $token = new AccessToken();

        $this->assertNotNull($token);

        $this->assertFalse($token->isNonceDisabled());
        $token->nonceDisable();
        $this->assertTrue($token->isNonceDisabled());
    }

    public function testNonceEnable()
    {
        $token = new AccessToken();

        $this->assertNotNull($token);

        $this->assertFalse($token->isNonceDisabled());
        $token->nonceEnable();
        $this->assertFalse($token->isNonceDisabled());
    }
}
