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

This is a self-contained PHP implementation of BTCPayServer's cryptographically secure API: https://github.com/btcpayserver/btcpayserver-doc/blob/master/docs/CustomIntegration.md

# Before you start

If your application requires BitPay compatibility go to this repository instead https://github.com/btcpayserver/php-bitpay-client

The files in "examples" are migrated from previous versions and are UNTESTED. If someone can review the example files and improve them, please help out.

# Important upgrade notes

## Version 0.2.0
- The latest changes made in BitPay's v4 API are now reflected in this API.
- If you previously had "401 unauthorized" issues with this API, these should now be fixed. 
- This PHP API no longer depends on Symfony, making this framework independent. If you were using this API in combination with Symfony and require a Symfony service, you can get the old files from the `old-master` branch. We recommended you to keep the Symfony stuff separate.

## Version 0.1.2
- Previous versions were not numbered.

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

## Documentation

Please see the ``docs`` directory for information on how to use this library
and the ``examples`` directory for examples on using this library. You should
be able to run all the examples by running ``php examples/File.php``.

The ``examples/tutorial`` directory provides four scripts that guide you with creating a BTCPayServer invoice:
https://github.com/btcpayserver/btcpayserver-php-client/blob/master/examples/tutorial/

# Support

* https://github.com/btcpayserver/btcpayserver-php-client/issues
