# HPP (Hosted Payment Pages)

This part is implemented by [hpp-manual](https://docs.adyen.com/manuals/hpp-manual/hosted-payment-pages)

##### Sample usage of `PaymentControl`

###### In Presenter

```php
use \MetisFW\Adyen\Payment\HPP\SimplePaymentOperation;
use \MetisFW\Adyen\Payment\UI\HPP\PaymentControlFactory;
use Nette\Application\UI\Presenter;
use Nette\Utils\DateTime;
use \MetisFW\Adyen\Payment\HPP\ResultPayment;

class MyPresenter extends Presenter {

  public function createComponentAdyenPaymentButton(SimplePaymentOperationFactory $factory, PaymentControlFactory $controlFactory) {
    $sessionValidity = (new DateTime())->modify('+ 1 hour');
    $shipDate = (new DateTime())->modify('+ 1 hour');
    $operation = $factory->create('VS12345678', 20000, 'EUR', $sessionValidity, $shipDate);
    $control = $$controlFactory->create($operation);
  
    //set different template if u want to use own
    $control->setTemplateFilePath(__DIR__ . './myAdyenButton.latte');
  
 
    //called after the payment completed the payment process
    $control->onReturn[] = function(PaymentControl $control, ResultPayment $resultPayment) {
      //something
    };
  
    //called when some error happened during the payment process
    $control->onError[] = function(PaymentControl $control) {
      //something
    };
  
    return $control;
  }
}
```

###### In latte

```latte
#just
{control payPalPaymentButton}

#or

#cannot use attributes directly in control
# see http://doc.nette.org/en/2.3/default-macros#toc-component-rendering
{var attributes = array('class' => 'adyen-payment-button')} 
{control adyenPaymentButton $attributes, 'Pay me now!'}
```

##### Sample usage of `SimplePaymentOperation`

```php
  public function createComponentAdyenPaymentButton(SimplePaymentOperationFactory $factory, PaymentControlFactory $controlFactory) {
    $sessionValidity = (new DateTime())->modify('+ 1 hour');
    $shipDate = (new DateTime())->modify('+ 1 hour');
    $operation = $factory->create('VS12345678', 20000, 'EUR', $sessionValidity, $shipDate);
    $control = $$controlFactory->create($operation);
    return $control;
  }
```

##### Sample usage of own descendant `\MetisFW\Adyen\Payment\BasePaymentOperation`

```php
<?php

namespace MetisApp\Components\Payment;

use MetisFW\Adyen\Payment\HPP\BasePaymentOperation;
use MetisFW\Adyen\AdyenContext;
use MetisFW\Adyen\Payment\HPP\Payment;

class OrderAdyenOperation extends BasePaymentOperation {

  /** @var mixed */
  private $order;

  /**
   * @param AdyneContext $context
   * @param mixed $order some data - object/array/...
   */
  public function __construct(PayPalContext $context, $order) {
    parent::__construct($context);
    $this->order = $order;
  }

  /**
   * @return void
   */
  protected function initializePayment(Payment $payment) {
    // setup payment via data passed in constructor
  }

}

```

###### Events in Operation
```php
  public function createComponentPayPalPaymentButton(FactorType $factory) {
    $operation = $factory->create();
    
    // called when payment was authorised
    $operation->onReturn[] = function($operation, $paymentResult) {
      //something
    }
    
    // called when payment was not authorised
    $operation->onCancel[] = function($operation, $paymentResult) {
      //something
    }
    
    ...
  }
```
