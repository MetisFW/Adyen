<?php

namespace MetisFW\Adyen\Payment\HPP;

use Nette\Application\Request;
use Nette\Object;

class PaymentResult extends Object {

  /** @var string */
  private $authResult;

  /** @var string */
  private $pspReference;

  /** @var string */
  private $merchantReference;

  /** @var string */
  private $skinCode;

  /** @var string */
  private $merchantReturnData;

  /** @var string */
  private $paymentMethod;

  /** @var string */
  private $shopperLocale;

  /**
   * @return string
   */
  public function getAuthResult() {
    return $this->authResult;
  }

  /**
   * @param string $authResult
   */
  public function setAuthResult($authResult) {
    $this->authResult = $authResult;
  }

  /**
   * @return string
   */
  public function getPspReference() {
    return $this->pspReference;
  }

  /**
   * @param string $pspReference
   */
  public function setPspReference($pspReference) {
    $this->pspReference = $pspReference;
  }

  /**
   * @return string
   */
  public function getMerchantReference() {
    return $this->merchantReference;
  }

  /**
   * @param string $merchantReference
   */
  public function setMerchantReference($merchantReference) {
    $this->merchantReference = $merchantReference;
  }

  /**
   * @return string
   */
  public function getSkinCode() {
    return $this->skinCode;
  }

  /**
   * @param string $skinCode
   */
  public function setSkinCode($skinCode) {
    $this->skinCode = $skinCode;
  }

  /**
   * @return string
   */
  public function getMerchantReturnData() {
    return $this->merchantReturnData;
  }

  /**
   * @param string $merchantReturnData
   */
  public function setMerchantReturnData($merchantReturnData) {
    $this->merchantReturnData = $merchantReturnData;
  }

  /**
   * @return string
   */
  public function getPaymentMethod() {
    return $this->paymentMethod;
  }

  /**
   * @param string $paymentMethod
   */
  public function setPaymentMethod($paymentMethod) {
    $this->paymentMethod = $paymentMethod;
  }

  /**
   * @return string
   */
  public function getShopperLocale() {
    return $this->shopperLocale;
  }

  /**
   * @param string $shopperLocale
   */
  public function setShopperLocale($shopperLocale) {
    $this->shopperLocale = $shopperLocale;
  }

  /**
   * @return array
   */
  public function getSignatureValues() {
    $values = array(
      'authResult' => $this->getAuthResult(),
      'merchantReference' => $this->getMerchantReference(),
      'merchantReturnData' => $this->getMerchantReturnData(),
      'paymentMethod' => $this->getPaymentMethod(),
      'pspReference' => $this->getPspReference(),
      'shopperLocale' => $this->getShopperLocale(),
      'skinCode' => $this->getSkinCode()
    );
    return $values;
  }

  public function isAuthorised() {
    return $this->authResult === 'AUTHORISED';
  }

  /**
   * @param Request $request
   * @return PaymentResult
   */
  public static function createFromRequest(Request $request) {
    $paymentResult = new PaymentResult();
    $paymentResult->setAuthResult($request->getParameter('authResult'));
    $paymentResult->setPspReference($request->getParameter('pspReference'));
    $paymentResult->setMerchantReference($request->getParameter('merchantReference'));
    $paymentResult->setSkinCode($request->getParameter('skinCode'));
    $paymentResult->setMerchantReturnData($request->getParameter('merchantReturnData'));
    $paymentResult->setShopperLocale($request->getParameter('shopperLocale'));
    $paymentResult->setPaymentMethod($request->getParameter('paymentMethod'));
    return $paymentResult;
  }

}
