<?php

namespace MetisFWTests\Adyen\Payment\HPP;

use MetisFW\Adyen\Payment\HPP\BasePaymentOperation;
use MetisFW\Adyen\Payment\HPP\Payment;
use Nette\Utils\DateTime;

class DummyPaymentOperation extends BasePaymentOperation {

  /**
   * Set specific payment properties
   *
   * @param Payment $payment
   * @return void
   */
  public function initializePayment(Payment $payment) {
    $payment->setMerchantReference('12345678');
    $payment->setPaymentAmount(10000);

    $today = new DateTime();
    $shipDate = $today->modifyClone('+ 10 days');
    $payment->setShipBeforeDate($shipDate);

    $sessionValidity = $today->modifyClone('+ 30 minutes');
    $payment->setSessionValidity($sessionValidity);
  }

}
