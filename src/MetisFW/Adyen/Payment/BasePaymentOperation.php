<?php

namespace MetisFW\Adyen\Payment;

use MetisFW\Adyen\AdyenContext;
use Nette\Object;

abstract class BasePaymentOperation extends Object implements PaymentOperation {

  /** @var AdyenContext */
  private $context;

  /**
   * @param AdyenContext $context
   */
  public function __construct(AdyenContext $context) {
    $this->context = $context;
  }

  /**
   * Set specific payment properties
   *
   * @param Payment $payment
   * @return void
   */
  abstract function initializePayment(Payment $payment);

  /**
   * Get new instance of payment object
   *
   * @return Payment
   */
  public function getPayment() {
    $payment = new Payment();
    $defaults = $this->context->getDefaultPaymentParameters();
    $payment->setDefaults($defaults);

    $this->initializePayment($payment);
    return $payment;
  }

  /**
   * @param Payment $payment
   * @return Payment
   */
  public function signPayment(Payment $payment) {
    $hmacKey = $this->context->getHmacKey();
    $skinCode = $this->context->getSkinCode();
    $merchantAccount = $this->context->getMerchantAccount();
    $payment->sign($hmacKey, $skinCode, $merchantAccount);
    return $payment;
  }

}
