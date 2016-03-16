<?php

namespace MetisFW\Adyen\Payment;


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

}
