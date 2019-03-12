##  Creating Your Own Storage Class
With the PHP library you are able to configure how your keys are stored
along with where your keys are stored. This section of the documentation
will go over how to create a new storage service.

The first step is to implement the
[StorageInterface](https://github.com/btcpayserver/btcpayserver-php-client/blob/master/src/BTCPayServer/Storage/StorageInterface.php).

``` {.sourceCode .php}
use BTCPayServer\Storage\StorageInterface;

class ArrayStorage implements StorageInterface
{
    public function persist(\BTCPayServer\KeyInterface $key)
    {
        // code will go here
    }

    public function load($id)
    {
        // code will go here
    }
}
```

You can use a class like this to store your keys in a database, S3, or
any other place you can think of. Now to use your new Storage class, you
inject it into the
[KeyManager](https://github.com/btcpayserver/btcpayserver-php-client/blob/master/src/BTCPayServer/KeyManager.php)

``` {.sourceCode .php}
$storage = new ArrayStorage();
$manager = new \BTCPayServer\KeyManager($storage);
```

Now all you do is pass in the keys to be persisted or loaded.

``` {.sourceCode .php}
$manager->persist($key);
```

That's all there is to it.
