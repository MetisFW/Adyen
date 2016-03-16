<?php

namespace MetisFW\Adyen\Payment\HPP;

use Nette\Application\Request;

interface PaymentOperation {

  /**
   * Create paypal payment
   *
   * @return Payment
   */
  public function getPayment();

  /**
   * Sign payment
   *
   * @param Payment $payment
   *
   * @return Payment
   */
  public function signPayment(Payment $payment);

  /**
   * Handle return part of payment proccess
   *
   * @param Request $request
   * @return PaymentResult
   */
  public function handleReturn(Request $request);

  /**
   * Return payment endpoint url
   *
   * @return string
   */
  public function getEndpointUrl();

}
