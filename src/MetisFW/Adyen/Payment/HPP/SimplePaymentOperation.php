<?php

namespace MetisFW\Adyen\Payment\HPP;

use MetisFW\Adyen\AdyenContext;
use Nette\Utils\DateTime;

class SimplePaymentOperation extends BasePaymentOperation {

  /** @var string */
  private $merchantReference;

  /** @var int */
  private $paymentAmount;

  /** @var string */
  private $currencyCode;

  /** @var DateTime */
  private $sessionValidity;

  /** @var DateTime */
  private $shipBeforeDate;

  /**
   * @param AdyenContext $context
   * @param string $merchantReference
   * @param int $paymentAmount
   * @param string $currencyCode
   * @param DateTime $sessionValidity
   * @param DateTime $shipBeforeDate
   */
  public function __construct(
    AdyenContext $context,
    $merchantReference,
    $paymentAmount,
    $currencyCode,
    DateTime $sessionValidity,
    DateTime $shipBeforeDate
  ) {
    parent::__construct($context);
    $this->merchantReference = $merchantReference;
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
    $payment->setMerchantReference($this->merchantReference);
    $payment->setCurrencyCode($this->currencyCode);
    $payment->setPaymentAmount($this->paymentAmount);
    $payment->setSessionValidity($this->sessionValidity);
    $payment->setShipBeforeDate($this->shipBeforeDate);
  }

}
