# BtcPay PHP Client
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/btcpayserver/php-btcpay-client-v2/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/btcpayserver/btcpayserver-php-client.svg?style=flat-square)](https://packagist.org/packages/btcpayserver/btcpayserver-php-client)

This is a self-contained PHP implementation of BTCPayServer's cryptographically secure API: https://docs.btcpayserver.org/CustomIntegration/

## Getting Started

To get up and running with our PHP library quickly, follow [The GUIDE](https://github.com/btcpayserver/btcpayserver-php-client/blob/master/GUIDE.md)

## Support

* https://github.com/btcpayserver/btcpayserver-php-client/issues

## Important upgrade notes

### Version 2.0.0

Please note that this repository has gone through some big changes between v1.0.0 and v2.0.0
BitPay even created a whole seperate repository for these changes: As they completly redesigned the external interfaces of this SDK.

**Warning: Please be very careful when upgrading to v2.0.0. You will have to make changes to your code.**

### Version 1.0.0

(placeholder)

### Version 0.2.0
- The latest changes made in BitPay's v4 API are now reflected in this API.
- If you previously had "401 unauthorized" issues with this API, these should now be fixed. 
- This PHP API no longer depends on Symfony, making this framework independent. If you were using this API in combination with Symfony and require a Symfony service, you can get the old files from the `old-master` branch. We recommended you to keep the Symfony stuff separate.

### Version 0.1.2
- Previous versions were not numbered.

## Installation

### Composer

#### Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
```

#### Install using composer

```bash
php composer.phar require btcpayserver/btcpayserver-php-client
```

## Configuration

See https://github.com/btcpayserver/btcpayserver-php-client/tree/master/examples

## Contribute

To contribute to this project, please fork and submit a pull request.

## License

MIT License

Copyright (c) 2019 BitPay

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
