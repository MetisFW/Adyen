<?php

namespace MetisFW\Adyen\Payment\HPP;


interface BasicPaymentOperationFactory {

  /**
   * @return BasicPaymentOperation
   */
  public function create();

}
