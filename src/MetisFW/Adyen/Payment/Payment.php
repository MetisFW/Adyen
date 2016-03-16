<?php

namespace MetisFW\Adyen\Payment;

use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * @link https://docs.adyen.com/manuals/hpp-manual/hosted-payment-pages/hpp-payment-fields
 */
class Payment extends Object {

  /** @var string */
  private $hmacKey;

  /** @var string */
  private $skinCode;

  /** @var string */
  private $merchantAccount;

  /** @var string */
  private $merchantReference;

  /** @var int */
  private $paymentAmount;

  /** @var string */
  private $currencyCode;

  /** @var \DateTime */
  private $shipBeforeDate;

  /** @var \DateTime */
  private $sessionValidity;

  /** @var string */
  private $shopperLocale;

  /** @var string */
  private $orderData;

  /** @var string */
  private $merchantReturnData;

  /** @var string */
  private $countryCode;

  /** @var string */
  private $shopperEmail;

  /** @var string */
  private $shopperReference;

  /** @var string */
  private $allowedMethods;

  /** @var string */
  private $blockedMethods;

  /** @var string */
  private $offset;

  /** @var string */
  private $brandCode;

  /** @var string */
  private $issuerId;

  /** @var string */
  private $shopperStatement;

  /** @var string */
  private $offerEmail;

  /** @var string */
  private $resURL;

  /*
   * Getters & Setters
   */

  private function setSkinCode($value) {
    $this->setValue("skinCode", $value);
  }

  public function getSkinCode() {
    return $this->skinCode;
  }

  private function setMerchantAccount($value) {
    $this->setValue("merchantAccount", $value);
  }

  public function getMerchantAccount() {
    return $this->merchantAccount;
  }

  public function setMerchantReference($value) {
    $this->setValue("merchantReference", $value);
  }

  public function getMerchantReference() {
    return $this->merchantReference;
  }

  public function setPaymentAmount($value) {
    if(!is_integer($value)) {
      throw new InvalidArgumentException("Property paymentAmount has to be integer, ".
        get_class($value)." given");
    }
    $this->setValue("paymentAmount", $value);
  }

  public function getPaymentAmount() {
    return $this->paymentAmount;
  }

  public function setCurrencyCode($value) {
    $this->setValue("currencyCode", $value);
  }

  public function getCurrencyCode() {
    return $this->currencyCode;
  }

  public function setShipBeforeDate($value) {
    if(!($value instanceof \DateTime)) {
      throw new InvalidArgumentException("Property shipBeforeDate has to be instance of \\DateTime, instance of ".
        gettype($value)." given");
    }
    $this->setValue('shipBeforeDate', $value);
  }

  public function getShipBeforeDate() {
    return $this->shipBeforeDate;
  }

  public function setSessionValidity($value) {
    if(!($value instanceof \DateTime)) {
      throw new InvalidArgumentException("Property sessionValidity has to be instance of \\DateTime, instance of ".
        gettype($value)." given");
    }
    $this->setValue('sessionValidity', $value);
  }

  public function getSessionValidity() {
    return $this->sessionValidity;
  }

  public function setShopperLocale($value) {
    $this->setValue('shopperLocale', $value);
  }

  public function getShopperLocale() {
    return $this->shopperLocale;
  }

  /**
   * @return string
   */
  public function getResURL() {
    return $this->resURL;
  }

  /**
   * @param mixed $resURL
   */
  public function setResURL($value) {
    $this->setValue('resUrl', $value);
  }

  /**
   * @return mixed
   */
  public function getOfferEmail() {
    return $this->offerEmail;
  }

  /**
   * @param mixed $offerEmail
   */
  public function setOfferEmail($value) {
    $this->setValue('offerEmail', $value);
  }

  /**
   * @return mixed
   */
  public function getShopperStatement() {
    return $this->shopperStatement;
  }

  /**
   * @param mixed $shopperStatement
   */
  public function setShopperStatement($value) {
    $this->setValue('shopperStatement', $value);
  }

  /**
   * @return mixed
   */
  public function getIssuerId() {
    return $this->issuerId;
  }

  /**
   * @param mixed $issuerId
   */
  public function setIssuerId($value) {
    $this->setValue('issuerId', $value);
  }

  /**
   * @return mixed
   */
  public function getBrandCode() {
    return $this->brandCode;
  }

  /**
   * @param mixed $brandCode
   */
  public function setBrandCode($value) {
    $this->setValue('brandCode', $value);
  }

  /**
   * @return mixed
   */
  public function getOffset() {
    return $this->offset;
  }

  /**
   * @param mixed $offset
   */
  public function setOffset($value) {
    $this->setValue('offset', $value);
  }

  /**
   * @return mixed
   */
  public function getBlockedMethods() {
    return $this->blockedMethods;
  }

  /**
   * @param mixed $blockedMethods
   */
  public function setBlockedMethods($value) {
    $this->setValue('blockedMethods', $value);
  }

  /**
   * @return mixed
   */
  public function getAllowedMethods() {
    return $this->allowedMethods;
  }

  /**
   * @param mixed $allowedMethods
   */
  public function setAllowedMethods($value) {
    $this->setValue('allowedMethods', $value);
  }

  /**
   * @return mixed
   */
  public function getShopperReference() {
    return $this->shopperReference;
  }

  /**
   * @param mixed $shopperReference
   */
  public function setShopperReference($value) {
    $this->setValue('shopperReference', $value);
  }

  /**
   * @return mixed
   */
  public function getShopperEmail() {
    return $this->shopperEmail;
  }

  /**
   * @param mixed $shopperEmail
   */
  public function setShopperEmail($value) {
    $this->setValue('shopperEmail', $value);
  }

  /**
   * @return mixed
   */
  public function getCountryCode() {
    return $this->countryCode;
  }

  /**
   * @param mixed $countryCode
   */
  public function setCountryCode($value) {
    $this->setValue('countryCode', $value);
  }

  /**
   * @return mixed
   */
  public function getMerchantReturnData() {
    return $this->merchantReturnData;
  }

  /**
   * @param mixed $merchantReturnData
   */
  public function setMerchantReturnData($value) {
    $this->setValue('merchantReturnData', $value);
  }

  /**
   * @return mixed
   */
  public function getOrderData() {
    return $this->orderData;
  }

  /**
   * @param mixed $orderData
   */
  public function setOrderData($value) {
    $this->setValue('orderData', $value);
  }

  /*
   * Other methods
   */

  public function isEditable() {
    return !$this->isSigned();
  }

  public function isSigned() {
    return $this->hmacKey != null && $this->skinCode != null && $this->merchantAccount != null;
  }

  public function isReady() {
    return $this->isSigned() && $this->merchantReference != null && $this->paymentAmount != null &&
    $this->currencyCode != null && $this->shipBeforeDate != null && $this->sessionValidity != null;
  }

  public function setDefaults(array $defaults) {
    foreach($defaults as $key => $value) {
      $this->$key = $value;
    }
  }

  public function getHPPValues() {
    if(!$this->isReady()) {
      throw new InvalidStateException('Cannot get values for HPP (Host Payment pages).'.
        ' Payment has not filled all required fields');
    }

    $result = $this->getArrayValues();
    $result['merchantSig'] = $this->calculateSignature($this->hmacKey);
    return $result;
  }

  /**
   * @param string $hmacKey
   * @param string $skinCode
   * @param string $merchantAccount
   *
   * @return void
   */
  public function sign($hmacKey, $skinCode, $merchantAccount) {
    $this->hmacKey = $hmacKey;
    $this->setSkinCode($skinCode);
    $this->setMerchantAccount($merchantAccount);
  }

  private function calculateSignature($hmacKey) {
    // The character escape function
    $escapeval = function ($val) {
      return str_replace(':', '\\:', str_replace('\\', '\\\\', $val));
    };

    $params = $this->getArrayValues();
    // Sort the array by key using SORT_STRING order
    ksort($params, SORT_STRING);

    // Generate the signing data string
    $signData = implode(":", array_map($escapeval, array_merge(array_keys($params), array_values($params))));

    // base64-encode the binary result of the HMAC computation
    $merchantSig = base64_encode(hash_hmac('sha256', $signData, pack("H*", $hmacKey), true));
    return $merchantSig;
  }

  private function setValue($propertyName, $value) {
    if($this->isSigned()) {
      throw new InvalidArgumentException("Cannot set property of already signed entity");
    }
    $this->$propertyName = $value;
  }

  /**
   * @return array
   */
  private function getArrayValues() {
    $values = array(
      'skinCode' => $this->skinCode,
      'merchantAccount' => $this->merchantAccount,
      'merchantReference' => $this->merchantReference,
      'paymentAmount' => $this->paymentAmount,
      'currencyCode' => $this->currencyCode,
      'shipBeforeDate' => $this->shipBeforeDate->format('Y-m-d'),
      'shopperLocale' => $this->shopperLocale,
      'orderData' => $this->orderData,
      'merchantReturnData' => $this->merchantReturnData,
      'countryCode' => $this->countryCode,
      'shopperEmail' => $this->shopperEmail,
      'shopperReference' => $this->shopperReference,
      'allowedMethods' => $this->allowedMethods,
      'blockedMethods' => $this->blockedMethods,
      'offset' => $this->offset,
      'brandCode' => $this->brandCode,
      'issuerId' => $this->issuerId,
      'shopperStatement' => $this->shopperStatement,
      'offerEmail' => $this->offerEmail,
      'resURL' => $this->resURL
    );

    // php converts UTC timezone to '+00:00' instead to 'Z'
    $sessionValidity = $this->sessionValidity->format(\DateTime::ATOM);
    if (Strings::endsWith($sessionValidity, '+00:00')) {
      $sessionValidity = Strings::replace($sessionValidity, '|\+00:00|', 'Z');
    }
    $values['sessionValidity'] = $sessionValidity;

    // filter non null/undefined fields
    $result = array_filter($values,
      function ($value) {
        return $value != null;
      }
    );
    return $result;
  }

}
