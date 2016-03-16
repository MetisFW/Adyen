<?php

namespace MetisFW\Adyen\Payment\Notification;

use MetisFW\Adyen\AdyenException;

class InvalidNotificationException extends AdyenException {

  /** @var NotificationRequestItem */
  private $item;

  /**
   * @param string $message
   * @param int $code
   * @param NotificationRequestItem $item
   * @param \Exception|null $previous
   */
  public function __construct($message, $code, NotificationRequestItem $item, \Exception $previous = null) {
    $this->item = $item;
    parent::__construct($message, $code, $previous);
  }

  /**
   * @return NotificationRequestItem
   */
  public function getNotificationRequestItem() {
    return $this->item;
  }

}
