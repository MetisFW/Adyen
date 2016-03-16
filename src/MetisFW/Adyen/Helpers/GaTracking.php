<?php

namespace MetisFW\Adyen\Helpers;

use MetisFW\Adyen\Payment\HPP\Payment;
use Nette\Http\Url;
use Nette\Object;

class GaTracking extends Object {

  private function __construct() {
    // nothing
  }

  /**
   * @param Payment $payment
   * @return Payment
   */
  public static function addTrackingParameters(Payment $payment) {
    $resURL = $payment->getResURL();

    $url = new Url($resURL);
    $url->setQueryParameter('utm_nooverride', 1);
    $payment->setResURL($url->getAbsoluteUrl());

    return $payment;
  }

}
