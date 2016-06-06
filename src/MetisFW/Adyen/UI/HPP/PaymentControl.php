<?php

namespace MetisFW\Adyen\UI\HPP;

use MetisFW\Adyen\AdyenException;
use MetisFW\Adyen\Payment\HPP\Payment;
use MetisFW\Adyen\Payment\HPP\PaymentOperation;
use Nette\Application\Request;
use Nette\Application\UI\Control;

class PaymentControl extends Control {

  /** @var PaymentOperation */
  private $operation;

  /** @var Request */
  private $request;

  /** @var string */
  private $templateFilePath;

  /**
   * @var array of callbacks, signature function(PaymentControl $control, PaymentResult $paymentResult)
   */
  public $onReturn;

  /**
   * @var array of callbacks, signature function(PaymentControl control, $exception)
   */
  public $onError;

  /**
   * @var array of callbacks, signature function(PaymentControl control)
   */
  public $onCheckout;

  /**
   * @param Request $request
   * @param PaymentOperation $operation
   */
  public function __construct(Request $request, PaymentOperation $operation) {
    parent::__construct();
    $this->request = $request;
    $this->operation = $operation;
  }

  public function setTemplateFilePath($templateFilePath) {
    $this->templateFilePath = $templateFilePath;
  }

  public function getTemplateFilePath() {
    return $this->templateFilePath ? $this->templateFilePath : $this->getDefaultTemplateFilePath();
  }

  public function handleReturn() {
    try {
      $resultPayment = $this->operation->handleReturn($this->request);
    } catch (AdyenException $exception) {
      $this->errorHandler($exception);
      return;
    }

    $this->onReturn($this, $resultPayment);
  }

  /**
   * @param \Exception $exception
   *
   * @throws AdyenException
   *
   * @return void
   */
  protected function errorHandler(\Exception $exception) {
    if(!$this->onError) {
      throw $exception;
    }

    $this->onError($this, $exception);
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
    $template->text = $text;
    $template->attrs = $attrs;
    $template->operation = $this->operation;

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

  /**
   * @return void
   */
  public function handleCheckout() {
    $this->onCheckout($this);
  }

}
