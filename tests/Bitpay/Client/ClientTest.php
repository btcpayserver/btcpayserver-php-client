<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace BTCPayServer\Client;

date_default_timezone_set('UTC');

class ChildOfClient extends Client
{
    public function checkPriceAndCurrency($price, $currency) {
        return parent::checkPriceAndCurrency($price, $currency);
    }
}

class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new Client();
        $this->client->setUri('https://btcpay.server/');
        $this->client->setToken($this->getMockToken());
        $this->client->setPublicKey($this->getMockPublicKey());
        $this->client->setPrivateKey($this->getMockPrivateKey());
        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($this->getMock('BTCPayServer\Client\ResponseInterface'));
        $this->client->setAdapter($adapter);
    }

    public function testCheckPriceAndCurrency() {
        $client = new ChildOfClient();
        $res = $client->checkPriceAndCurrency(.999999, 'BTC');
        $this->assertNull($res);
        $res = $client->checkPriceAndCurrency(1000, 'USD');
        $this->assertNull($res);
        $res = $client->checkPriceAndCurrency(0, 'USD');
        $this->assertNull($res);
        $res = $client->checkPriceAndCurrency(.01, 'USD');
        $this->assertNull($res);
        $res = $client->checkPriceAndCurrency(99, 'USD');
        $this->assertNull($res);
        $res = $client->checkPriceAndCurrency(100.9, 'USD');
        $this->assertNull($res);
    }

    /**
     * @expectedException \BTCPayServer\Client\BTCPayServerException
     * @expectedExceptionMessage You should provider the url of your BTCPAY server
     */
    public function testBtcPayServerUrlNotProvided()
    {
        $client = new Client();
        $client->getTokens();
    }

    /**
     * @expectedException \Exception
     */
    public function testCreatePayoutWithException()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn('{"error":"Error with request"}');

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $currency = $this->getMockCurrency();
        $currency->method('getCode')->will($this->returnValue('USD'));

        $token = $this->getMockToken();

        $payout = new \BTCPayServer\Payout();
        $payout
            ->setCurrency($currency)
            ->setEffectiveDate("1415853007000")
            ->setPricingMethod('bitcoinbestbuy')
            ->setToken($token);

        $this->client->createPayout($payout);
    }

    public function testCreatePayout()
    {
        $currency = $this->getMockCurrency();
        $currency->method('getCode')->will($this->returnValue('USD'));

        $token = $this->getMockToken();

        $payout = new \BTCPayServer\Payout();
        $payout
            ->setCurrency($currency)
            ->setEffectiveDate("1415853007000")
            ->setPricingMethod('bitcoinbestbuy')
            ->setNotificationUrl('https://btcpayserver.com')
            ->setNotificationEmail('support@btcpayserver.com')
            ->setPricingMethod('bitcoinbestbuy')
            ->setReference('your reference, can be json')
            ->setAmount(5625)
            ->setToken($token);

        $btc_amounts = array(
            \BTCPayServer\PayoutInstruction::STATUS_UNPAID => null,
            \BTCPayServer\PayoutInstruction::STATUS_PAID => '0'
        );
        $instruction0 = new \BTCPayServer\PayoutInstruction();
        $instruction0
            ->setId('Sra19AFU57Rx53rKQbbRKZ')
            ->setAmount(1875)
            ->setLabel('2')
            ->setStatus(\BTCPayServer\PayoutInstruction::STATUS_UNPAID)
            ->setBtc($btc_amounts)
            ->setAddress('mzzsJ8G9KBmHPPVYaMxpYRetWRRec78FvF');

        $instruction1 = new \BTCPayServer\PayoutInstruction();
        $instruction1
            ->setId('5SCdU1xNsEwrUFqKChYuAR')
            ->setAmount(1875)
            ->setLabel('3')
            ->setStatus(\BTCPayServer\PayoutInstruction::STATUS_UNPAID)
            ->setBtc($btc_amounts)
            ->setAddress('mre3amN8KCFuy7gWCjhFXjuqkmoJMkd2gx');

        $instruction2 = new \BTCPayServer\PayoutInstruction();
        $instruction2
            ->setId('5cHNbnmNuo8gRawnrFZsPy')
            ->setAmount(1875)
            ->setLabel('4')
            ->setStatus(\BTCPayServer\PayoutInstruction::STATUS_UNPAID)
            ->setBtc($btc_amounts)
            ->setAddress('mre3amN8KCFuy7gWCjhFXjuqkmoJMkd2gx');

        $payout
            ->addInstruction($instruction0)
            ->addInstruction($instruction1)
            ->addInstruction($instruction2);

        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/payouts/7m7hSF3ws1LhnWUf17CXsJ.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $payout = $this->client->createPayout($payout);
        $this->assertInstanceOf('BTCPayServer\PayoutInterface', $payout);
        $this->assertEquals('7m7hSF3ws1LhnWUf17CXsJ', $payout->getId());
        $this->assertEquals('Lwbnf9XAPCxDmy8wsRH3ct', $payout->getAccountId());
        $this->assertEquals(\BTCPayServer\Payout::STATUS_NEW, $payout->getStatus());
        $this->assertEquals(5625, $payout->getAmount());
        $this->assertEquals(null, $payout->getRate());
        $this->assertEquals(null, $payout->getBtcAmount());
        $this->assertEquals('bitcoinbestbuy', $payout->getPricingMethod());
        $this->assertEquals('your reference, can be json', $payout->getReference());
        $this->assertEquals('1415853007000', $payout->getEffectiveDate());
        $this->assertEquals('https://btcpayserver.com', $payout->getNotificationUrl());
        $this->assertEquals('support@btcpayserver.com', $payout->getNotificationEmail());
        $this->assertEquals('8mZ37Gt91Wr7GXGPnB9zj1zwTcLGweRDka4axVBPi9Uxiiv7zZWvEKSgmFddQZA1Jy', $payout->getResponseToken());
        $instructions = $payout->getInstructions();
        $this->assertSame($instruction0, $instructions[0]);
        $this->assertSame($instruction1, $instructions[1]);
        $this->assertSame($instruction2, $instructions[2]);
    }

    public function testCreateInvoice()
    {
        $buyer = $this->getMockBuyer();
        $buyer->method('getAddress')->will($this->returnValue(array()));

        $currency = $this->getMockCurrency();
        $currency->method('getCode')->will($this->returnValue('USD'));

        $invoice = new \BTCPayServer\Invoice();
        $invoice->setOrderId('TEST-01');

        $invoice->setCurrency($currency);

        $item = new \BTCPayServer\Item();
        $item->setPrice('19.95');
        $invoice->setItem($item);

        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/invoice.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $invoice = $this->client->createInvoice($invoice);
        $this->assertInstanceOf('BTCPayServer\InvoiceInterface', $invoice);
        $this->assertEquals('abcdefghijkmnopqrstuvw', $invoice->getId());
        $this->assertEquals('https://test.btcpayserver.com/invoice?id=abcdefghijkmnopqrstuvw', $invoice->getUrl());
        $this->assertEquals('new', $invoice->getStatus());
        //$this->assertEquals('0.0632', $invoice->getBtcPrice());
        $this->assertEquals(19.95, $invoice->getPrice());
        $this->assertInstanceOf('DateTime', $invoice->getInvoiceTime());
        $this->assertInstanceOf('DateTime', $invoice->getExpirationTime());
        $this->assertInstanceOf('DateTime', $invoice->getCurrentTime());
        //$this->assertEquals('0.0000', $invoice->getBtcPaid());
        $this->assertEquals(315.7, $invoice->getRate());
        $this->assertEquals(false, $invoice->getExceptionStatus());
        $this->assertEquals('abcdefghijklmno', $invoice->getToken()->getToken());
    }

    /**
     * @expectedException Exception
     */
    public function testCreateResponseWithException()
    {
        $item = $this->getMockItem();
        $item->method('getPrice')->will($this->returnValue(1));

        $buyer = $this->getMockBuyer();
        $buyer->method('getAddress')->will($this->returnValue(array()));

        $invoice = $this->getMockInvoice();
        $invoice->method('getItem')->willReturn($item);
        $invoice->method('getBuyer')->willReturn($buyer);
        $invoice->method('setId')->will($this->returnSelf());
        $invoice->method('setUrl')->will($this->returnSelf());
        $invoice->method('setStatus')->will($this->returnSelf());
        //$invoice->method('setBtcPrice')->will($this->returnSelf());
        $invoice->method('setPrice')->will($this->returnSelf());
        $invoice->method('setInvoiceTime')->will($this->returnSelf());
        $invoice->method('setExpirationTime')->will($this->returnSelf());
        $invoice->method('setCurrentTime')->will($this->returnSelf());
        //$invoice->method('setBtcPaid')->will($this->returnSelf());
        $invoice->method('setRate')->will($this->returnSelf());
        $invoice->method('setExceptionStatus')->will($this->returnSelf());
        $invoice->method('getCurrency')->willReturn($this->getMockCurrency());

        $response = $this->getMockResponse();
        $response->method('getBody')->will($this->returnValue('{"error":"Some error message"}'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $this->client->createInvoice($invoice);
    }

    /**
     * @depends testCreateInvoice
     */
    public function testGetResponse()
    {
        $this->assertNull($this->client->getResponse());
    }

    /**
     * @depends testCreateInvoice
     */
    public function testGetRequest()
    {
        $this->assertNull($this->client->getRequest());
    }

    /**
     * @depends testGetRequest
     * @depends testGetResponse
     * @expectedException Exception
     */
    public function testCreateInvoiceWithError()
    {
        $this->assertNull($this->client->getResponse());
        $this->assertNull($this->client->getRequest());

        $invoice = $this->getMockInvoice();
        $invoice->method('setId')->will($this->returnSelf());
        $invoice->method('setUrl')->will($this->returnSelf());
        $invoice->method('setStatus')->will($this->returnSelf());
        //$invoice->method('setBtcPrice')->will($this->returnSelf());
        $invoice->method('setPrice')->will($this->returnSelf());
        $invoice->method('setInvoiceTime')->will($this->returnSelf());
        $invoice->method('setExpirationTime')->will($this->returnSelf());
        $invoice->method('setCurrentTime')->will($this->returnSelf());
        //$invoice->method('setBtcPaid')->will($this->returnSelf());
        $invoice->method('setRate')->will($this->returnSelf());
        $invoice->method('setExceptionStatus')->will($this->returnSelf());
        $invoice->method('getCurrency')->willReturn($this->getMockCurrency());
        $invoice->method('getItem')->willReturn($this->getMockItem());
        $invoice->method('getBuyer')->willReturn($this->getMockBuyer());

        $adapter = $this->getMockAdapter();
        $response = $this->getMockResponse();
        $response->method('getBody')->will($this->returnValue('{"error":"Some error message"}'));
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        // throws exception
        $this->client->createInvoice($invoice);
    }

    /**
     * @expectedException Exception
     */
    public function testGetCurrenciesWithException()
    {
        $this->client->getCurrencies();
    }

    public function testGetCurrencies()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/currencies.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $currencies = $this->client->getCurrencies();

        $this->assertInternalType('array', $currencies);
        $this->assertGreaterThan(0, count($currencies));
        $this->assertInstanceOf('BTCPayServer\CurrencyInterface', $currencies[0]);
    }

    public function testGetPayouts()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/payouts/getpayouts.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $payouts = $this->client->getPayouts();
        $this->assertInternalType('array', $payouts);
        $this->assertInstanceOf('BTCPayServer\PayoutInterface', $payouts[0]);

    }

    /**
     * @expectedException \Exception
     */
    public function testGetPayoutsWithException()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn('{"error":"Some error message"}');

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $payouts = $this->client->getPayouts();

    }

    public function testGetTokens()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/with_tokens.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $tokens = $this->client->getTokens();
        $this->assertInternalType('array', $tokens);
        $this->assertSame('39zPuHaBbO8VMZe8Bdr9RjmRY6pHT7Gs3ifcbKM6PYSg2', $tokens['payroll']->getToken());
        $this->assertSame('payroll', $tokens['payroll']->getFacade());

        $this->assertSame('5QziWnr75x7c4B9DdJ5QUo', $tokens['payroll/payoutRequest']->getToken());
        $this->assertSame('payroll/payoutRequest', $tokens['payroll/payoutRequest']->getFacade());
    }

    /**
     * @expectedException \Exception
     */
    public function testGetTokensWithException()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn('{"error":""}');

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $tokens = $this->client->getTokens();
    }

    public function testCreateToken()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/tokens.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $token = $this->client->createToken();
        $this->assertInstanceOf('BTCPayServer\TokenInterface', $token);

        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/tokens_pairing.json'));

        $token = $this->client->createToken();
        $this->assertInstanceOf('BTCPayServer\TokenInterface', $token);
    }

    /**
     * @expectedException Exception
     */
    public function testCreateTokenWithException()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn('{"error":""}');

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);
        $this->client->createToken(array('id'=>'','pairingCode'=>''));
    }

    public function testGetInvoice()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/invoices/5NxFkXcJbCSivtQRJa4kHP.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);
        $token = new \BTCPayServer\Token();
        $token->setToken('asdfsds');

        // No token/public facade
        $invoice = $this->client->getInvoice('5NxFkXcJbCSivtQRJa4kHP');
        $this->assertSame('invoices/5NxFkXcJbCSivtQRJa4kHP', $this->client->getRequest()->getPath());
        $this->assertInstanceOf('BTCPayServer\InvoiceInterface', $invoice);

        // pos token/public facade
        $this->client->setToken($token->setFacade('pos'));
        $invoice = $this->client->getInvoice('5NxFkXcJbCSivtQRJa4kHP');
        $this->assertSame('invoices/5NxFkXcJbCSivtQRJa4kHP', $this->client->getRequest()->getPath());
        $this->assertInstanceOf('BTCPayServer\InvoiceInterface', $invoice);

        // merchant token/merchant facade
        $this->client->setToken($token->setFacade('merchant'));
        $invoice = $this->client->getInvoice('5NxFkXcJbCSivtQRJa4kHP');
        $this->assertSame('invoices/5NxFkXcJbCSivtQRJa4kHP?token=asdfsds', $this->client->getRequest()->getPath());
        $this->assertInstanceOf('BTCPayServer\InvoiceInterface', $invoice);
    }

    /**
     * @expectedException Exception
     */
    public function testGetInvoiceException()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn('{"error":"Object not found"}');
        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);
        $this->client->getInvoice('5NxFkXcJbCSivtQRJa4kHP');
    }

    public function testGetPayout()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/payouts/complete.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);
        $payout = $this->client->getPayout('7m7hSF3ws1LhnWUf17CXsJ');
        $this->assertInstanceOf('BTCPayServer\PayoutInterface', $payout);
        $this->assertSame($payout->getId(), '7AboMecD4jSMXbH7DaJJvm');
        $this->assertSame($payout->getAccountId(), 'Lwbnf9XAPCxDmy8wsRH3ct');
        $this->assertSame($payout->getStatus(), 'complete');
        $this->assertSame($payout->getRate(), 352.23);
        $this->assertSame($payout->getAmount(), 5625);
        $this->assertSame($payout->getBtcAmount(), 15.9696);
        $this->assertSame($payout->getCurrency()->getCode(), 'USD');
    }

    /**
     * @expectedException Exception
     */
    public function testGetPayoutException()
    {
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn('{"error":"Object not found"}');

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);
        $this->client->getPayout('5NxFkXcJbCSivtQRJa4kHP');
    }

    public function testDeletePayout()
    {
        // Set up using getPayout
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/payouts/7m7hSF3ws1LhnWUf17CXsJ.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $payout = $this->client->getPayout('7m7hSF3ws1LhnWUf17CXsJ');


        // Test deletePayout
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/payouts/cancelled.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $payout = $this->client->deletePayout($payout);

        $this->assertSame($payout->getStatus(), \BTCPayServer\Payout::STATUS_CANCELLED);
    }

    /**
     * @expectedException \Exception
     */
    public function testDeletePayoutWithException()
    {
        // Setup using getPayout
        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn(file_get_contents(__DIR__ . '/../../DataFixtures/payouts/7m7hSF3ws1LhnWUf17CXsJ.json'));

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $payout = $this->client->getPayout('7m7hSF3ws1LhnWUf17CXsJ');

        // Test with exception

        $response = $this->getMockResponse();
        $response->method('getBody')->willReturn('{"error":"Object not found"}');

        $adapter = $this->getMockAdapter();
        $adapter->method('sendRequest')->willReturn($response);
        $this->client->setAdapter($adapter);

        $payout = $this->client->deletePayout($payout);
        $this->assertSame($payout->getStatus(), \BTCPayServer\Payout::STATUS_CANCELLED);
    }


    private function getMockInvoice()
    {
        $invoice = $this->getMockBuilder('BTCPayServer\InvoiceInterface')
            ->setMethods(
                array(
                    'getPrice', 'getTaxIncluded', 'getCurrency', 'getItem', 'getBuyer', 'getTransactionSpeed',
                    'getNotificationEmail', 'getNotificationUrl', 'getRedirectUrl', 'getPosData', 'getStatus',
                    'isFullNotifications', 'getId', 'getUrl', 'getBtcPrice', 'getInvoiceTime',
                    'getExpirationTime', 'getCurrentTime', 'getOrderId', 'getItemDesc', 'getItemCode',
                    'isPhysical', 'getBuyerName', 'getBuyerAddress1', 'getBuyerAddress2', 'getBuyerCity',
                    'getBuyerState', 'getBuyerZip', 'getBuyerCountry', 'getBuyerEmail', 'getBuyerPhone',
                    'getExceptionStatus', 'getBtcPaid', 'getRate', 'getToken', 'getRefundAddresses',
                    'setId', 'setUrl', 'setStatus', 'setBtcPrice', 'setPrice', 'setInvoiceTime', 'setExpirationTime',
                    'setCurrentTime', 'setBtcPaid', 'setRate', 'setToken', 'setExceptionStatus', 'isExtendedNotifications',
                    'setPosData',
                )
            )
            ->getMock();

        return $invoice;
    }

    private function getMockPayout()
    {
        $invoice = $this->getMockBuilder('BTCPayServer\PayoutInterface')
            ->setMethods(
                array(
                    'getId',
                    'setId',
                    'getAccountId',
                    'setAccountId',
                    'getAmount',
                    'getCurrency',
                    'setCurrency',
                    'getEffectiveDate',
                    'setEffectiveDate',
                    'getRate',
                    'setRate',
                    'getRequestDate',
                    'setRequestDate',
                    'getInstructions',
                    'addInstruction',
                    'updateInstruction',
                    'getStatus',
                    'setStatus',
                    'getToken',
                    'setToken',
                    'getResponseToken',
                    'setResponseToken',
                    'getPricingMethod',
                    'setPricingMethod',
                    'getReference',
                    'setReference',
                    'getNotificationEmail',
                    'setNotificationEmail',
                    'getNotificationUrl',
                    'setNotificationUrl',
                )
            )
            ->getMock();

        return $invoice;
    }

    private function getMockBuyer()
    {
        return $this->getMockBuilder('BTCPayServer\BuyerInterface')
            ->setMethods(
                array(
                    'getPhone',
                    'getEmail',
                    'getFirstName',
                    'getLastName',
                    'getAddress',
                    'getCity',
                    'getState',
                    'getZip',
                    'getCountry',
                    'getNotify'
                )
            )
            ->getMock();
    }

    private function getMockItem()
    {
        return $this->getMockBuilder('BTCPayServer\ItemInterface')
            ->setMethods(
                array(
                    'getCode',
                    'getDescription',
                    'getPrice',
                    'getTaxIncluded',
                    'getQuantity',
                    'isPhysical',
                )
            )
            ->getMock();
    }

    private function getMockCurrency()
    {
        return $this->getMockBuilder('BTCPayServer\CurrencyInterface')
            ->setMethods(
                array(
                    'getCode',
                    'getSymbol',
                    'getPrecision',
                    'getExchangePctFee',
                    'isPayoutEnabled',
                    'getName',
                    'getPluralName',
                    'getAlts',
                    'getPayoutFields',
                )
            )
            ->getMock();
    }

    private function getMockToken()
    {
        return $this->getMock('BTCPayServer\TokenInterface');
    }

    private function getMockAdapter()
    {
        return $this->getMock('BTCPayServer\Client\Adapter\AdapterInterface');
    }

    private function getMockPublicKey()
    {
        return $this->getMock('BTCPayServer\PublicKey');
    }

    private function getMockPrivateKey()
    {
        return $this->getMock('BTCPayServer\PrivateKey');
    }

    private function getMockResponse()
    {
        return $this->getMock('BTCPayServer\Client\ResponseInterface');
    }
}
