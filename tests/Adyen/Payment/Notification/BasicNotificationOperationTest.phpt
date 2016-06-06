<?php

namespace MetisFWTests\Adyen\Payment\Notification;

use MetisFW\Adyen\AdyenContext;
use MetisFW\Adyen\Payment\Notification\BasicNotificationOperation;
use MetisFW\Adyen\Payment\Notification\NotificationRequestItem;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__.'/../../../bootstrap.php';

class BasicNotificationOperationTest extends TestCase {

  /** @var AdyenContext */
  private $adyen;

  /** @var BasicNotificationOperation */
  private $operation;

  /** @var int */
  private $onNotificationCounter;

  /** @var int */
  private $onFailedCounter;

  /** @var int */
  private $onSuccessCounter;

  /** @var int */
  private $onAuthorisedCounter;

  /** @var int */
  private $onCancelledCounter;

  /** @var int */
  private $onRefundCounter;

  /** @var int */
  private $onOtherCounter;

  public function setUp() {
    parent::setUp();

    $skinCode = '123456';
    $merchantAccount = 'TestMerchant';
    $hmacKey = '009E9E92268087AAD241638D3325201AFC8AAE6F3DCD369B6D32E87129FFAB10';
    $this->adyen = new AdyenContext($skinCode, $merchantAccount, $hmacKey);
    $operation = new BasicNotificationOperation($this->adyen);

    $this->onNotificationCounter = 0;
    $operation->onNotification[] = function (BasicNotificationOperation $operation, NotificationRequestItem $item) {
      $this->onNotificationCounter++;
    };

    $this->onFailedCounter = 0;
    $operation->onFailed[] = function (BasicNotificationOperation $operation, NotificationRequestItem $item) {
      $this->onFailedCounter++;
    };

    $this->onSuccessCounter = 0;
    $operation->onSuccess[] = function (BasicNotificationOperation $operation, NotificationRequestItem $item) {
      $this->onSuccessCounter++;
    };

    $this->onAuthorisedCounter = 0;
    $operation->onAuthorised[] = function (BasicNotificationOperation $operation, NotificationRequestItem $item) {
      $this->onAuthorisedCounter++;
    };

    $this->onCancelledCounter = 0;
    $operation->onCancelled[] = function (BasicNotificationOperation $operation, NotificationRequestItem $item) {
      $this->onCancelledCounter++;
    };

    $this->onRefundCounter = 0;
    $operation->onRefund[] = function (BasicNotificationOperation $operation, NotificationRequestItem $item) {
      $this->onRefundCounter++;
    };

    $this->onOtherCounter = 0;
    $operation->onOther[] = function (BasicNotificationOperation $operation, NotificationRequestItem $item) {
      $this->onOtherCounter++;
    };

    $this->operation = $operation;
  }

  /**
   * @throws \MetisFW\Adyen\AdyenException
   */
  public function testRejectTestNotificationInLiveMode() {
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

    $request = $this->createRequest($rawBody);
    $this->adyen->setLive(true);
    $this->operation->handleNotification($request);
  }

  /**
   * @throws \MetisFW\Adyen\Payment\Notification\InvalidNotificationException
   */
  public function testRejectUnsignedNotification() {
    $rawBody = '
      {  
         "live":"true",
         "notificationItems":[  
            {  
               "notificationRequestItem":{  
                  "additionalData":{
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

    $request = $this->createRequest($rawBody);
    $this->adyen->setLive(true);
    $this->adyen->setAcceptUnsignedNotifications(false);


    $this->operation->handleNotification($request);
  }

  /**
   * @throws \MetisFW\Adyen\InvalidSignatureException
   */
  public function testRejectInvalidSignature() {
    $rawBody = '
      {  
         "live":"true",
         "notificationItems":[  
            {  
               "notificationRequestItem":{  
                  "additionalData":{
                     "hmacSignature":"invalid"
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

    $request = $this->createRequest($rawBody);
    $this->adyen->setLive(true);
    $this->adyen->setAcceptUnsignedNotifications(false);

    $this->operation->handleNotification($request);
  }

  public function testAcceptSignedNotification() {
    $rawBody = '
      {  
         "live":"true",
         "notificationItems":[  
            {  
               "notificationRequestItem":{  
                  "additionalData":{
                     "hmacSignature":"KI1966ZdInXariCqlfFBKHMGt+W1xfqUuOwKJFydaNM="
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
                  "success":"false"
               }
            }
         ]
      }
    ';

    $request = $this->createRequest($rawBody);
    $this->adyen->setLive(true);
    $this->operation->handleNotification($request);

    Assert::equal(1, $this->onNotificationCounter);
    Assert::equal(1, $this->onFailedCounter);

    Assert::equal(0, $this->onSuccessCounter);
    Assert::equal(0, $this->onAuthorisedCounter);
    Assert::equal(0, $this->onCancelledCounter);
    Assert::equal(0, $this->onRefundCounter);
    Assert::equal(0, $this->onOtherCounter);
  }

  public function testHandleAuthorisedNotification() {
    $rawBody = '
      {  
         "live":"true",
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

    $request = $this->createRequest($rawBody);
    $this->adyen->setLive(true);
    $this->operation->handleNotification($request);

    Assert::equal(1, $this->onNotificationCounter);
    Assert::equal(1, $this->onSuccessCounter);
    Assert::equal(1, $this->onAuthorisedCounter);

    Assert::equal(0, $this->onFailedCounter);
    Assert::equal(0, $this->onCancelledCounter);
    Assert::equal(0, $this->onRefundCounter);
    Assert::equal(0, $this->onOtherCounter);
  }

  public function testHandleCancelledNotification() {
    $rawBody = '
      {  
         "live":"true",
         "notificationItems":[  
            {  
               "notificationRequestItem":{  
                  "additionalData":{
                     "hmacSignature":"tZAgwW5sAYvVxrS80TXu+W1Q8cVmDVGcXq5enTFhOTM="
                  },
                  "amount":{  
                     "value":8650,
                     "currency":"EUR"
                  },
                  "pspReference":"7914073251449896",
                  "eventCode":"CANCELLATION",
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

    $request = $this->createRequest($rawBody);
    $this->adyen->setLive(true);
    $this->operation->handleNotification($request);

    Assert::equal(1, $this->onNotificationCounter);
    Assert::equal(1, $this->onSuccessCounter);
    Assert::equal(1, $this->onCancelledCounter);

    Assert::equal(0, $this->onAuthorisedCounter);
    Assert::equal(0, $this->onFailedCounter);
    Assert::equal(0, $this->onRefundCounter);
    Assert::equal(0, $this->onOtherCounter);
  }

  public function testHandleRefundNotification() {
    $rawBody = '
      {  
         "live":"true",
         "notificationItems":[  
            {  
               "notificationRequestItem":{  
                  "additionalData":{
                     "hmacSignature":"Zc2oIWWFAFPy6Wb47JJpRtgZ2KXJwqvgiBqbQ9ByDQA="
                  },
                  "amount":{  
                     "value":8650,
                     "currency":"EUR"
                  },
                  "pspReference":"7914073251449896",
                  "eventCode":"REFUND",
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

    $request = $this->createRequest($rawBody);
    $this->adyen->setLive(true);
    $this->operation->handleNotification($request);

    Assert::equal(1, $this->onNotificationCounter);
    Assert::equal(1, $this->onSuccessCounter);
    Assert::equal(1, $this->onRefundCounter);

    Assert::equal(0, $this->onAuthorisedCounter);
    Assert::equal(0, $this->onCancelledCounter);
    Assert::equal(0, $this->onFailedCounter);
    Assert::equal(0, $this->onOtherCounter);
  }

  public function testOtherNotification() {
    $rawBody = '
      {  
         "live":"true",
         "notificationItems":[  
            {  
               "notificationRequestItem":{  
                  "additionalData":{
                     "hmacSignature":"lQRtX7G2kj2xPAIs86DJe+2IZCKhqT0c6IVmhcyQuJM="
                  },
                  "amount":{  
                     "value":8650,
                     "currency":"EUR"
                  },
                  "pspReference":"7914073251449896",
                  "eventCode":"SOMETHING",
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

    $request = $this->createRequest($rawBody);
    $this->adyen->setLive(true);
    $this->operation->handleNotification($request);

    Assert::equal(1, $this->onNotificationCounter);
    Assert::equal(1, $this->onSuccessCounter);
    Assert::equal(1, $this->onOtherCounter);

    Assert::equal(0, $this->onAuthorisedCounter);
    Assert::equal(0, $this->onCancelledCounter);
    Assert::equal(0, $this->onRefundCounter);
    Assert::equal(0, $this->onFailedCounter);
  }

  private function createRequest($rawBody) {
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
      }
    );

    return $request;
  }

}

\run(new BasicNotificationOperationTest());
