<?php

namespace Bitpay;

class CurrencyUnrestricted extends Currency
{
  /**
   * Overrides the parent method to allow any currency symbol to be set.
   */
  public function setCode($code)
  {
    $this->code = strtoupper($code);

    return $this;
  }
}
