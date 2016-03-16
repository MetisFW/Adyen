<?php

namespace MetisFW\Adyen\Payment;

use Nette\Utils\DateTime;

interface BasicPaymentOperationFactory {

  /**
   * @param int $paymentAmount
   * @param string $currencyCode
   * @param DateTime $sessionValidity
   * @param DateTime $shipBeforeDate
   * @return BasicPaymentOperation
   */
  public function create($paymentAmount, $currencyCode, DateTime $sessionValidity, DateTime $shipBeforeDate);

}

