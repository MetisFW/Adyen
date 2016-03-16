<?php

namespace MetisFWTests\Adyen\Payment\Notification;

use MetisFW\Adyen\Payment\Notification\Notification;
use MetisFW\Adyen\Payment\Notification\NotificationRequestItem;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

class NotificationRequestItemTest extends TestCase {

  public function testIsAuthorised() {
    $item = $this->createItem('AUTHORISATION', true);
    Assert::true($item->isAuthorised());

    $item = $this->createItem('AUTHORISATION', false);
    Assert::false($item->isAuthorised());

    $item = $this->createItem('AUTHORISATIO', true);
    Assert::false($item->isAuthorised());
  }

  public function testIsCancelled() {
    $item = $this->createItem('CANCELLATION', true);
    Assert::true($item->isCancelled());

    $item = $this->createItem('CANCELLATION', false);
    Assert::false($item->isCancelled());

    $item = $this->createItem('CANCEL_OR_REFUND', true);
    Assert::true($item->isCancelled());

    $item = $this->createItem('CANCEL_OR_REFUND', false);
    Assert::false($item->isCancelled());

    $item = $this->createItem('CANCELLATIO', true);
    Assert::false($item->isCancelled());
  }

  public function testIsRefund() {
    $item = $this->createItem('REFUND', true);
    Assert::true($item->isRefund());

    $item = $this->createItem('REFUND', false);
    Assert::false($item->isRefund());

    $item = $this->createItem('CANCEL_OR_REFUND', true);
    Assert::true($item->isRefund());

    $item = $this->createItem('CANCEL_OR_REFUND', false);
    Assert::false($item->isRefund());

    $item = $this->createItem('REFUN', true);
    Assert::false($item->isRefund());
  }

  /**
   * @param string $eventCode
   * @param bool $success
   *
   * @return NotificationRequestItem
   */
  private function createItem($eventCode, $success) {
    $item = new NotificationRequestItem(new Notification());
    $item->setEventCode($eventCode);
    $item->setSuccess($success);
    return $item;
  }

}

\run(new NotificationRequestItemTest());
