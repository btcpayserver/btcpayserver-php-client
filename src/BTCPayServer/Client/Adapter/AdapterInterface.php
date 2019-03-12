<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer\Client\Adapter;

use BTCPayServer\Client\RequestInterface;
use BTCPayServer\Client\ResponseInterface;

/**
 * A client can be configured to use a specific adapter to make requests, by
 * default the CurlAdapter is what is used.
 *
 * @package BTCPayServer
 */
interface AdapterInterface
{
    /**
     * Send request to BTCPayServer
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request);
}
