<?php

namespace MetisFW\Adyen\Payment\Notification;

interface BasicNotificationOperationFactory {

  /**
   * @return BasicNotificationOperation
   */
  public function create();

}
