<?php

namespace MetisFW\Adyen\Payment;

use MetisFW\Adyen\AdyenContext;
use Nette\Utils\DateTime;

class BasicPaymentOperation extends BasePaymentOperation {

  private $paymentAmount;

  private $currencyCode;

  private $sessionValidity;

  private $shipBeforeDate;

  public function __construct(
    AdyenContext $context,
    $paymentAmount,
    $currencyCode,
    DateTime $sessionValidity,
    DateTime $shipBeforeDate
  ) {
    parent::__construct($context);
    $this->paymentAmount = $paymentAmount;
    $this->currencyCode = $currencyCode;
    $this->sessionValidity = $sessionValidity;
    $this->shipBeforeDate = $shipBeforeDate;
  }

  /**
   * Set specific payment properties
   *
   * @param Payment $payment
   * @return void
   */
  function initializePayment(Payment $payment) {
    $payment->setCurrencyCode($this->currencyCode);
    $payment->setPaymentAmount($this->paymentAmount);
    $payment->setSessionValidity($this->sessionValidity);
    $payment->setShipBeforeDate($this->shipBeforeDate);
  }

}
