<?php

namespace MetisFW\Adyen\Payment\Notification;

use Nette\Http\Request;
use Nette\InvalidArgumentException;
use Nette\Object;
use Nette\Utils\DateTime;
use Nette\Utils\Json;

/**
 * @link https://docs.adyen.com/manuals/api-manual/notifications/notification-fields
 */
class Notification extends Object {

  /** @var bool */
  private $live;

  /** @var array */
  private $notificationItems;

  /**
   * @return bool
   */
  public function isLive() {
    return $this->live;
  }

  /**
   * @param bool $live
   */
  public function setLive($live) {
    $this->live = $live;
  }

  /**
   * @return array
   */
  public function getNotificationItems() {
    return $this->notificationItems;
  }

  /**
   * @param array $notificationItems
   *
   * @return void
   */
  private function setNotificationItems(array $notificationItems) {
    foreach($notificationItems as $notificationItem) {
      if(!$notificationItem instanceof NotificationRequestItem) {
        throw new InvalidArgumentException('Invalid type of item in array. '.
          'Expected \Adyen\Payment\Notification\NotificationRequestItem but '.get_class($notificationItem).' given.');
      }
    }

    $this->notificationItems = $notificationItems;
  }

  /**
   * @param Request $request
   * @return Notification
   */
  public static function createFromRequest(Request $request) {
    $notification = new Notification();
    $parsed = Json::decode($request->getRawBody());
    $notification->setLive($parsed->live === 'true');

    $items = array();
    foreach($parsed->notificationItems as $rawItem) {
      $item = new NotificationRequestItem($notification);
      $item->setAdditionalData(self::getNotificationRequestItemValue($rawItem, 'additionalData'));
      $item->setAmountValue(self::getNotificationRequestItemValue($rawItem, 'amount.value'));
      $item->setAmountCurrency(self::getNotificationRequestItemValue($rawItem, 'amount.currency'));
      $item->setPspReference(self::getNotificationRequestItemValue($rawItem, 'pspReference'));
      $item->setEventCode(self::getNotificationRequestItemValue($rawItem, 'eventCode'));

      $date = new DateTime(self::getNotificationRequestItemValue($rawItem, 'eventDate'));
      $item->setEventDate($date);
      $item->setMerchantAccountCode(self::getNotificationRequestItemValue($rawItem, 'merchantAccountCode'));
      $item->setOperations(self::getNotificationRequestItemValue($rawItem, 'operations'));
      $item->setMerchantReference(self::getNotificationRequestItemValue($rawItem, 'merchantReference'));
      $item->setOriginalReference(self::getNotificationRequestItemValue($rawItem, 'originalReference'));
      $item->setPaymentMethod(self::getNotificationRequestItemValue($rawItem, 'paymentMethod'));
      $item->setReason(self::getNotificationRequestItemValue($rawItem, 'reason'));
      $item->setSuccess(self::getNotificationRequestItemValue($rawItem, 'success') === 'true');

      $items[] = $item;
    }
    $notification->setNotificationItems($items);
    return $notification;
  }

  private static function getNotificationRequestItemValue($rawItem, $key) {
    $result = $rawItem->notificationRequestItem;
    $minimalKeys = explode('.', $key);
    foreach($minimalKeys as $minimalKey) {
      if(isset($result->$minimalKey)) {
        $result = $result->$minimalKey;
      } else {
        return null;
      }
    }
    return $result;
  }

}
