<?php

namespace MetisFW\Adyen\UI\HPP;

use MetisFW\Adyen\Payment\HPP\PaymentOperation;
use Nette\Application\Request;

interface PaymentControlFactory {

  /**
   * @param Request $request
   * @param PaymentOperation $operation
   * @return PaymentControl
   */
  public function create(Request $request, PaymentOperation $operation);

}