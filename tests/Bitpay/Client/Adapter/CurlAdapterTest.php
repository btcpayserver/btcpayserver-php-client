<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace BTCPayServer\Client\Adapter;

use BTCPayServer\Client\Request;

class CurlAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $request;

    public function setUp()
    {
        $this->request = new Request();
    }

    public function testConstruct()
    {
        $adapter = new CurlAdapter();
        $this->assertNotNull($adapter->getCurlOptions());
    }

    public function testGetCurlOptions()
    {
        $adapter = new CurlAdapter();
        $this->assertEquals(array(), $adapter->getCurlOptions());
    }

    /**
     * @expectedException \BTCPayServer\Client\ConnectionException
     */
    public function testSendRequestWithException()
    {
        $curl_options = array(
            CURLOPT_URL            => 'btcpay.example.com',
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_SSL_VERIFYHOST => 2,
        );

        $adapter = new CurlAdapter($curl_options);
        $adapter->sendRequest($this->request);
    }

    public function testSendRequestWithoutException()
    {
        $curl_options = array(
            CURLOPT_URL            => 'www.btcpayserver.com',
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_SSL_VERIFYHOST => 2,
        );

        $adapter = new CurlAdapter($curl_options);
        $response = $adapter->sendRequest($this->request);
        $this->assertNotNull($response);
    }

}
