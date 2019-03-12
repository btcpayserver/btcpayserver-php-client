##  Invoices
Creating an invoice allows you to accept payment in bitcoins. You can
also query the BTCPayServer's system to find out more information about the
invoice that you created.

Working with an Invoice Object
==============================

Every invoice can have lots of data that can be used and sent to BTCPayServer
as reference. Feel free to take a look at `BTCPayServer\InvoiceInterface` for
code comments on what each method returns and a more in depth
explanation.

First we need to create a new Invoice object.

``` {.sourceCode .php}
$invoice = new \BTCPayServer\Invoice();
```

> **note**
>
> You can also set an Order ID that you can use to reference BTCPayServer's
> invoice with the invoice in your order system.
>
> `$invoice->setOrderId('You Order ID here')`

To make an invoice valid, it needs a price and a currency. You can see a
list of currencies supported by viewing the [Bitcoin Exchange
Rates](https://btcpayserver.com/bitcoin-exchange-rates) page on our website.

For this example, we will use `USD` as our currency of choice.

``` {.sourceCode .php}
$invoice->setCurrency(new \BTCPayServer\Currency('USD'));
```

Now the invoice knows what currency to use. Next it needs a price.

``` {.sourceCode .php}
$item = new \BTCPayServer\Item();
$item->setPrice('19.95');
$invoice->setItem($item);
```

The only thing left is to now send the invoice off to BTCPayServer for the
invoice to be created and for you to send it to your customer.

Creating an Invoice
===================

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

> **warning**
>
> If you are running a command line script as a different user, you
> could get a different \$HOME directory. Please be aware. Also the keys
> are chmod'ed when written to disk so the private key can only be read
> by the owner.

Next you will need to get the client.

``` {.sourceCode .php}
// @var \BTCPayServer\Client\Client
$client = $btcpay->get('client');
```

Inject your `TokenObject` into the client.

``` {.sourceCode .php}
$token = new \BTCPayServer\Token();
$token->setToken('Insert Token Here');
$client->setToken($token);
```

Now all you need to do is send the `$invoice` object to BTCPayServer.

``` {.sourceCode .php}
$client->createInvoice($invoice);
```

The code will update the `$invoice` object and you will be able to
forward your customer to BTCPayServer's fullscreen invoice.

``` {.sourceCode .php}
header('Location: ' . $invoice->getUrl());
```

Instant Payment Notifications (IPN)
===================================

You can enabled IPNs for an invoice by setting the notificationUrl.
Example:

``` {.sourceCode .php}
$invoice->setNotificationUrl('https://example.com/btcpayserver/ipn');
```

By adding the Notification URL, it will receive an IPN when the invoice
is updated. For more information on IPNs, please see the documentation
on BTCPayServer's website.
