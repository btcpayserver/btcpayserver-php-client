##  Pairing
To create a pairing code, please open the API Tokens page within your
merchant account. You will next need to click the button labeled "Add
New Token". You will be given a Pairing Code that you will need in the
few steps.

Pairing {#pairing-1}
=======

Create an instance of the BTCPayServer class.

``` {.sourceCode .php}
$btcpay = new \BTCPayServer\BTCPayServer(
    array(
        'btcpay' => array(
            'network'     => 'testnet', // testnet or livenet, default is livenet
            'public_key'  => getenv('HOME').'/.btcpayserver/api.pub',
            'private_key' => getenv('HOME').'/.btcpayserver/api.key',
        )
    )
);
```

Next you will need to get the client.

``` {.sourceCode .php}
// @var \BTCPayServer\Client\Client
$client = $btcpay->get('client');
```

You will next need to create a SIN based on your Public Key.

``` {.sourceCode .php}
// @var \BTCPayServer\KeyManager
$manager   = $btcpay->get('key_manager');
$publicKey = $manager->load($btcpay->getContainer()->getParameter('btcpayserver.public_key'));
$sin = new \BTCPayServer\SinKey();
$sin->setPublicKey($publicKey);
$sin->generate();

// @var \BTCPayServer\TokenInterface
$token = $client->createToken(
    array(
        'id'          => (string) $sin,
        'pairingCode' => 'Enter the Pairing Code',
        'label'       => 'Any label you want',
    )
);
```

> **note**
>
> Want to know more about SINs? See
> <https://en.bitcoin.it/wiki/Identity_protocol_v1>

The token that you get back will be needed to be sent with future
requests.
