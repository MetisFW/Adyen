<?php

namespace MetisFW\Adyen;

use MetisFW\Adyen\Payment\Notification\InvalidNotificationException;
use MetisFW\Adyen\Payment\Notification\NotificationRequestItem;

class InvalidSignatureException extends InvalidNotificationException {

  /**
   * @param NotificationRequestItem $item
   */
  public function __construct(NotificationRequestItem $item) {
    parent::__construct('Received notification has invalid signature', 0, $item);
  }

}
