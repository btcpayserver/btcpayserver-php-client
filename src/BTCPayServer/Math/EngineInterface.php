<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer\Math;

/**
 * @package Bitcore
 */
interface EngineInterface
{
    /**
     * @param String $a Numeric String
     * @param String $b Numeric String
     */
    public function add($a, $b);

    /**
     * @param String $a Numeric String
     * @param String $b Numeric String
     */
    public function cmp($a, $b);

    /**
     * @param String $a Numeric String
     * @param String $b Numeric String
     */
    public function div($a, $b);

    /**
     * @param String $a Numeric String
     * @param String $b Numeric String
     */
    public function invertm($a, $b);

    /**
     * @param String $a Numeric String
     * @param String $b Numeric String
     */
    public function mod($a, $b);

    /**
     * @param String $a Numeric String
     * @param String $b Numeric String
     */
    public function mul($a, $b);

    /**
     * @param String $a Numeric String
     * @param String $b Numeric String
     */
    public function pow($a, $b);

    /**
     * @param String $a Numeric String
     * @param String $b Numeric String
     */
    public function sub($a, $b);
}
