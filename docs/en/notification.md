# Notifications

This part is implemented by [api-manual](https://docs.adyen.com/manuals/api-manual#notifications)

##### Sample usage of `BasicNotificationOperation`

###### In Presenter

```php
use \MetisFW\Adyen\Payment\Notification\BasicNotificationOperation;
use \MetisFW\Adyen\Payment\Notification\BasicNotificationOperationFactory;
use Nette\Application\UI\Presenter;
use MetisFW\Adyen\Payment\Notification\NotificationRequestItem;

class PaymentNotificationPresenter extends Presenter {

  /** @var BasicNotificationOperationFactory @inject */
  public $adyenNotificationOperationFactory;
    
    
  public function actionAdyen() {
    $operation = $adyenNotificationOperationFactory->create();
    
    // called for every notification that is received
    $operation->onNotification[] = function ($operation, NotificationRequestItem $item) {
      // something
    };
    
    // called for every notification that is unsuccessfully processed
    $operation->onFailed[] = function ($operation, NotificationRequestItem $item) {
      // something
    };
    
    // called for every notification that is successfully processed
    $operation->onSuccess[] = function ($operation, NotificationRequestItem $item) {
      // something
    };
    
    // called for every notification that is successfull and payment was authorised (payment was correctly processed)
    $operation->onAuthorised[] = function ($operation, NotificationRequestItem $item) {
      // something
    };
    
    // called for every notification that is successfull and payment was cancelled
    $operation->onCancelled[] = function ($operation, NotificationRequestItem $item) {
      // something
    };
    
    // called for every notification that is successfull and payment was refunded
    $operation->onRefund[] = function ($operation, NotificationRequestItem $item) {
      // something
    };
    
    // called for every notification that is successfull and happened something different than authorisation, cancellation, refund
    $operation->onCancelled[] = function ($operation, NotificationRequestItem $item) {
      // something
    };

    $httpRequest = $this->getHttpRequest();
    try {
      $operation->handleNotification($httpRequest);
    } catch (\Exception $exception) {
      $response = $operation->getErrorResponse();
      $this->sendResponse($response);
    }
    
    $response = $operation->getSuccessResponse();
    $this->sendResponse($response);
  }

}
```
