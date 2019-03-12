<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer\Util;

/**
 * Generates secure random numbers
 */
class SecureRandom
{
    /**
     * @var boolean
     */
    protected static $hasOpenSSL;

    /**
     * @return string
     */
    public static function generateRandom($bytes = 32)
    {
        if (!self::hasOpenSSL()) {
            throw new \Exception('You MUST have openssl module installed.');
        }

        $random = openssl_random_pseudo_bytes($bytes, $isStrong);

        if (!$random || !$isStrong) {
            throw new \Exception('Cound not generate a cryptographically strong random number.');
        }

        return $random;
    }

    /**
     * @return boolean
     */
    public static function hasOpenSSL()
    {
        if (null === self::$hasOpenSSL) {
            self::$hasOpenSSL = function_exists('openssl_random_pseudo_bytes');
        }

        return self::$hasOpenSSL;
    }
}
