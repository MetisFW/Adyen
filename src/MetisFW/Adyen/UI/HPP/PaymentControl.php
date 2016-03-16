<?php

namespace MetisFW\Adyen\UI\HPP;

use MetisFW\Adyen\AdyenContext;
use MetisFW\Adyen\Payment\Payment;
use MetisFW\Adyen\Payment\PaymentOperation;
use Nette\Application\UI\Control;

interface PaymentControlFactory {

  /**
   * @param PaymentOperation $operation
   * @return PaymentControl
   */
  public function create(PaymentOperation $operation);

}

class PaymentControl extends Control {

  /** @var PaymentOperation */
  private $operation;

  /** @var AdyenContext */
  private $adyen;

  /** @var string */
  private $templateFilePath;

  /** @var bool */
  private $checkoutEnabled = true;

  /**
   * @var array of callbacks, signature: function(PaymentControl $control, Payment $payment)
   */
  public $onCheckout;

  /**
   * @var array of callbacks, signature function(PaymentControl $control)
   */
  public $onReturn;

  /**
   * @param AdyenContext $adyen
   * @param PaymentOperation $operation
   */
  public function __construct(AdyenContext $adyen, PaymentOperation $operation) {
    parent::__construct();
    $this->adyen = $adyen;
    $this->operation = $operation;
  }

  public function setTemplateFilePath($templateFilePath) {
    $this->templateFilePath = $templateFilePath;
  }

  public function getTemplateFilePath() {
    return $this->templateFilePath ? $this->templateFilePath : $this->getDefaultTemplateFilePath();
  }

  public function setCheckoutEnableState($value) {
    $this->checkoutEnabled = $value;
  }

   // TODO ma to cenu?
  public function handleCheckout() {
    $payment = new Payment();
    $this->onCheckout($this, $payment);
  }

  public function handleReturn() {
    // todo nejaky dalsi parametry z returnu
    $this->onReturn($this);
  }

  /**
   * @param array $attrs
   * @param string $text
   *
   * @return void
   */
  public function render(array $attrs = array(), $text = "Pay") {
    $template = $this->template;
    $templateFilePath = $this->getTemplateFilePath();
    $template->setFile($templateFilePath);
    $template->checkoutLink = $this->link('//checkout!');
    $template->checkoutEnabled = $this->checkoutEnabled;
    $template->text = $text;
    $template->attrs = $attrs;
    $template->adyen = $this->adyen;

    $payment = $this->operation->getPayment();
    $this->setReturnUrl($payment);
    $template->payment = $this->operation->signPayment($payment);
    $template->render();
  }

  protected function getDefaultTemplateFilePath() {
    return __DIR__.'/templates/PaymentControl.latte';
  }

  private function setReturnUrl(Payment $payment) {
    $payment->setResURL($this->link('//return!'));
  }

}
