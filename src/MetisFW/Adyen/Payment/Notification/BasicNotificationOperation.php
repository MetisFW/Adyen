<?php

namespace MetisFW\Adyen\Payment\Notification;

use MetisFW\Adyen\AdyenContext;
use MetisFW\Adyen\AdyenException;
use MetisFW\Adyen\InvalidSignatureException;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\Responses\TextResponse;
use Nette\Http\Request;
use Nette\Http\Response;
use Nette\Object;

class BasicNotificationOperation extends Object implements NotificationOperation {

  /** @var array of callbacks, signature function($this, $item) */
  public $onNotification;

  /** @var array of callbacks, signature function($this, $item) */
  public $onFailed;

  /** @var array of callbacks, signature: function($this, $item) */
  public $onSuccess;

  /** @var array of callbacks, signature: function($this, $item) */
  public $onAuthorised;

  /** @var array of callbacks, signature: function($this, $item) */
  public $onCancelled;

  /** @var array of callbakcs, signature: function($this, $item) */
  public $onRefund;

  /** @var array of callbacks, signature: function($this, $item) */
  public $onOther;

  /** @var AdyenContext */
  private $adyen;

  /**
   * @param AdyenContext $adyen
   */
  public function __construct(AdyenContext $adyen) {
    $this->adyen = $adyen;
  }

  public function handleNotification(Request $request) {
    $notification = Notification::createFromRequest($request);
    if($notification->isLive() !== $this->adyen->isLive()) {
      // test notification on live environment or vice versa
      throw new AdyenException('Received notification is from test environment but extension is in live mode');
    }

    foreach($notification->getNotificationItems() as $item) {
      /** @var NotificationRequestItem $item */
      if(isset($item->getAdditionalData()->hmacSignature)) {
        $signature = $item->getAdditionalData()->hmacSignature;

        // check signature
        $signatureGenerator = $this->adyen->getSignaturesGenerator();
        $generated =$signatureGenerator->generateNotificationSignature($item);
        if ($signature !== $generated) {
          throw new InvalidSignatureException($item);
        }
      } else {
        // some reaction to processing not signed notification
        if(!$this->adyen->acceptUnsignedNotifications()) {
          throw new InvalidNotificationException(
            'Received notification is unsigned but extension is configured to accept only signed notifications',
            0,
            $item
          );
        }
      }
      $this->onNotification($this, $item);

      // standard process of notification
      if(!$item->isSuccess()) {
        $this->onFailed($this, $item); //onProblem, //onIssue
        continue;
      }

      $this->onSuccess($this, $item);

      if($item->isAuthorised()) {
        $this->onAuthorised($this, $item);
      } elseif($item->isCancelled()) {
        $this->onCancelled($this, $item);
      } elseif($item->isRefund()) {
        $this->onRefund($this, $item);
      } else {
        $this->onOther($this, $item);
      }

    }
  }

  /**
   * @return Response
   */
  public function getSuccessResponse() {
    $response = new JsonResponse(array('notificationResponse' => '[accepted]'));
    return $response;
  }

  /**
   * @return Response
   */
  public function getErrorResponse() {
    $response = new TextResponse('error l33t');
    return $response;
  }

}
