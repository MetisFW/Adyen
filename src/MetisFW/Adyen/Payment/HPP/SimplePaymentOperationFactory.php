<?php

namespace MetisFW\Adyen\Payment\HPP;

use Nette\Utils\DateTime;

interface SimplePaymentOperationFactory {

  /**
   * @param string $merchantReference
   * @param int $paymentAmount
   * @param string $currencyCode
   * @param DateTime $sessionValidity
   * @param DateTime $shipBeforeDate
   * @return SimplePaymentOperation
   */
  public function create(
    $merchantReference,
    $paymentAmount,
    $currencyCode,
    DateTime $sessionValidity,
    DateTime $shipBeforeDate
  );

}
