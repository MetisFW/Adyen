<?php

namespace MetisFW\Adyen\Payment\HPP;

use MetisFW\Adyen\AdyenContext;
use MetisFW\Adyen\Helpers\GaTracking;
use MetisFW\Adyen\SecurityException;
use Nette\Application\Request;
use Nette\InvalidArgumentException;
use Nette\Object;

abstract class BasePaymentOperation extends Object implements PaymentOperation {

  /** @var string */
  private $endpoint;

  /** @var AdyenContext */
  private $context;

  /** @var array of callbacks, signature function(BasePaymentOperation $operation, PaymentResult $paymentResult) */
  public $onReturn;

  /** @var array of callbacks, signature function(BasePaymentOperation $operation, PaymentResult $paymentResult) */
  public $onCancel;

  /**
   * @param AdyenContext $context
   */
  public function __construct(AdyenContext $context) {
    $this->context = $context;
  }

  /**
   * Set specific payment properties
   *
   * @param Payment $payment
   * @return void
   */
  abstract protected function initializePayment(Payment $payment);

  /**
   * Get new instance of payment object
   *
   * @return Payment
   */
  public function getPayment() {
    $payment = new Payment();
    $defaults = $this->context->getDefaultPaymentParameters();
    $payment->setDefaults($defaults);

    $this->initializePayment($payment);
    return $payment;
  }

  /**
   * @param Payment $payment
   * @return Payment
   */
  public function signPayment(Payment $payment) {
    $payment->setSkinCode($this->context->getSkinCode());
    $payment->setMerchantAccount($this->context->getMerchantAccount());

    if ($this->context->isGaTrackingEnabled()) {
      $payment = GaTracking::addTrackingParameters($payment);
    }

    $signature = $this->context->getSignaturesGenerator()->generatePaymentSignature($payment);
    $payment->sign($signature);
    return $payment;
  }

  /**
   * @param Request $request
   * @return PaymentResult
   * @throws SecurityException
   */
  public function handleReturn(Request $request) {
    $paymentResult = PaymentResult::createFromRequest($request);
    $recievedSignature = $request->getParameter('merchantSig');
    $computedSignature = $this->context->getSignaturesGenerator()->generatePaymentResultSignature($paymentResult);

    if($recievedSignature !== $computedSignature) {
      throw new SecurityException('Signatures does not match');
    }

    if ($paymentResult->isAuthorised()) {
      $this->onReturn($this, $paymentResult);
    } else {
      $this->onCancel($this, $paymentResult);
    }


    return $paymentResult;
  }

  /**
   * @param string $endpoint
   *
   * @return void
   */
  public function setEndpoint($endpoint) {
    if (!$this->context->isHppEndpointValid($endpoint)) {
      $allowedValues = array_keys($this->HPPEndpointMapping);
      throw new InvalidArgumentException('Invalid endpoint for HPP (Hosted Payment Pages).'.
        ' Possible values: \'' . implode(', ', $allowedValues) . '\' but ' . $endpoint . ' given.');
    }
    $this->endpoint = $endpoint;
  }

  /**
   * @return string
   */
  public function getEndpointUrl() {
    return $this->context->getHPPEndpointUrl($this->endpoint);
  }

}
