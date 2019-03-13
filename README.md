btcpayserver/btcpayserver-php-client
=================

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/btcpayserver/btcpayserver-php-client/master/LICENSE.md)
[![Travis](https://img.shields.io/travis/btcpayserver/btcpayserver-php-client.svg?style=flat-square)](https://travis-ci.org/btcpayserver/btcpayserver-php-client)
[![Packagist](https://img.shields.io/packagist/v/btcpayserver/btcpayserver-php-client.svg?style=flat-square)](https://packagist.org/packages/btcpayserver/btcpayserver-php-client)
[![Code Climate](https://img.shields.io/codeclimate/github/btcpayserver/btcpayserver-php-client.svg?style=flat-square)](https://codeclimate.com/github/btcpayserver/btcpayserver-php-client)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/btcpayserver/btcpayserver-php-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/btcpayserver/btcpayserver-php-client/)
[![Coveralls](https://img.shields.io/coveralls/btcpayserver/btcpayserver-php-client.svg?style=flat-square)](https://coveralls.io/r/btcpayserver/btcpayserver-php-client)

[![Documentation Status](https://readthedocs.org/projects/php-btcpay-client/badge/?version=latest)](https://readthedocs.org/projects/php-btcpay-client/?badge=latest)
[![Total Downloads](https://poser.pugx.org/btcpayserver/btcpayserver-php-client/downloads.svg)](https://packagist.org/packages/btcpayserver/btcpayserver-php-client)
[![Latest Unstable Version](https://poser.pugx.org/btcpayserver/btcpayserver-php-client/v/unstable.svg)](https://packagist.org/packages/btcpayserver/btcpayserver-php-client)

This is a self-contained PHP implementation of BTCPayServer's cryptographically secure API: https://github.com/btcpayserver/btcpayserver-doc/blob/master/CustomIntegration.md

# Before you start

If your application requires BitPay compatibility go to this repository instead https://github.com/btcpayserver/php-bitpay-client

# Installation

## Composer

### Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
```

### Install using composer

```bash
php composer.phar require btcpayserver/btcpayserver-php-client
```

# Configuration

See https://github.com/btcpayserver/btcpayserver-php-client/tree/master/examples

# Usage

## Autoloader

To use the library's autoloader (which doesn't include composer dependencies)
instead of composer's autoloader, use the following code:

```php
<?php
$autoloader = __DIR__ . '/relative/path/to/src/BTCPayServer/Autoloader.php';
if (true === file_exists($autoloader) &&
    true === is_readable($autoloader))
{
    require_once $autoloader;
    \BTCPayServer\Autoloader::register();
} else {
    throw new Exception('BTCPayServer Library could not be loaded');
}
```

## Documentation

Please see the ``docs`` directory for information on how to use this library
and the ``examples`` directory for examples on using this library. You should
be able to run all the examples by running ``php examples/File.php``.

The ``examples/tutorial`` directory provides four scripts that guide you with creating a BTCPayServer invoice:
https://github.com/btcpayserver/btcpayserver-php-client/blob/master/examples/tutorial/

# Support

* https://github.com/btcpayserver/btcpayserver-php-client/issues

# License

The MIT License (MIT)

Copyright (c) 2017 BTCPayServer, Inc.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
