<?php

namespace MetisFWTests\Adyen\Payment\Notification;

use MetisFW\Adyen\Payment\Notification\Notification;
use MetisFW\Adyen\Payment\Notification\NotificationRequestItem;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

class NotificationTest extends TestCase {

  public function testNotificationCreation() {
    $rawBody = '
      {  
         "live":"false",
         "notificationItems":[  
            {  
               "notificationRequestItem":{  
                  "additionalData":{
                     "hmacSignature":"S+5bAYKLd+L2A07Pal0pG/qBarnInaIe709YNzNcHOA="
                  },
                  "amount":{  
                     "value":8650,
                     "currency":"EUR"
                  },
                  "pspReference":"7914073251449896",
                  "eventCode":"AUTHORISATION",
                  "eventDate":"2014-08-06T17:15:34.121+02:00",
                  "merchantAccountCode":"TestMerchant",
                  "operations":[  
                     "CANCEL",
                     "CAPTURE",
                     "REFUND"
                  ],
                  "merchantReference":"TestPayment-1407325143704",
                  "paymentMethod":"visa",
                  "success":"true"
               }
            }
         ]
      }
    ';
    $request = new Request(new UrlScript('adyen-test'),
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      function () use ($rawBody) {
        return $rawBody;
      });

    $notification = Notification::createFromRequest($request);

    Assert::false($notification->isLive());
    Assert::true(is_array($notification->getNotificationItems()));
    Assert::count(1, $notification->getNotificationItems());
    /** @var NotificationRequestItem $notificationItem */
    $notificationItem = $notification->getNotificationItems()[0];

    Assert::true($notificationItem instanceof NotificationRequestItem);
    Assert::equal('S+5bAYKLd+L2A07Pal0pG/qBarnInaIe709YNzNcHOA=', $notificationItem->getAdditionalData()->hmacSignature);
    Assert::equal(8650, $notificationItem->getAmountValue());
    Assert::equal('EUR', $notificationItem->getAmountCurrency());
    Assert::equal('7914073251449896', $notificationItem->getPspReference());
    Assert::equal('AUTHORISATION', $notificationItem->getEventCode());
    Assert::equal('2014-08-06T17:15:34+0200', $notificationItem->getEventDate()->format(DateTime::ISO8601));
    Assert::equal('TestMerchant', $notificationItem->getMerchantAccountCode());
    Assert::equal('CANCEL', $notificationItem->getOperations()[0]);
    Assert::equal('CAPTURE', $notificationItem->getOperations()[1]);
    Assert::equal('REFUND', $notificationItem->getOperations()[2]);
    Assert::equal('TestPayment-1407325143704', $notificationItem->getMerchantReference());
    Assert::equal('visa', $notificationItem->getPaymentMethod());
    Assert::true($notificationItem->isSuccess());
  }
  
}

\run(new NotificationTest());
