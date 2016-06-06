<?php

namespace MetisFW\Adyen\Payment\HPP;

use MetisFW\Adyen\Payment\Platform\Address;
use MetisFW\Adyen\Payment\Platform\Shopper;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * @link https://docs.adyen.com/manuals/hpp-manual/hosted-payment-pages/hpp-payment-fields
 */
class Payment extends Object {

  /** @var string */
  private $signature;

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

  /** @var Address */
  private $deliveryAddress;

  /** @var Address */
  private $billingAddress;

  /** @var Shopper|null */
  private $shopper;

  /*
   * Getters & Setters
   */

  public function setShopper(Shopper $shopper = null) {
     $this->shopper = $shopper;
  }

  public function setBillingAddress(Address $address) {
    $this->billingAddress = $address;
  }

  public function getBillingAddress() {
    return $this->billingAddress;
  }

  public function setDeliveryAddress(Address $address) {
    $this->deliveryAddress = $address;
  }

  public function getDeliveryAddress() {
    return $this->deliveryAddress;
  }

  public function setSkinCode($value) {
    $this->setValue("skinCode", $value);
  }

  public function getSkinCode() {
    return $this->skinCode;
  }

  public function setMerchantAccount($value) {
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


  public function setResURL($value) {
    $this->setValue('resURL', $value);
  }

  /**
   * @return mixed
   */
  public function getOfferEmail() {
    return $this->offerEmail;
  }

  public function setOfferEmail($value) {
    $this->setValue('offerEmail', $value);
  }

  /**
   * @return mixed
   */
  public function getShopperStatement() {
    return $this->shopperStatement;
  }

  public function setShopperStatement($value) {
    $this->setValue('shopperStatement', $value);
  }

  /**
   * @return mixed
   */
  public function getIssuerId() {
    return $this->issuerId;
  }

  public function setIssuerId($value) {
    $this->setValue('issuerId', $value);
  }

  /**
   * @return mixed
   */
  public function getBrandCode() {
    return $this->brandCode;
  }

  public function setBrandCode($value) {
    $this->setValue('brandCode', $value);
  }

  /**
   * @return mixed
   */
  public function getOffset() {
    return $this->offset;
  }

  public function setOffset($value) {
    $this->setValue('offset', $value);
  }

  /**
   * @return mixed
   */
  public function getBlockedMethods() {
    return $this->blockedMethods;
  }

  public function setBlockedMethods($value) {
    $this->setValue('blockedMethods', $value);
  }

  /**
   * @return mixed
   */
  public function getAllowedMethods() {
    return $this->allowedMethods;
  }

  public function setAllowedMethods($value) {
    $this->setValue('allowedMethods', $value);
  }

  /**
   * @return mixed
   */
  public function getShopperReference() {
    return $this->shopperReference;
  }

  public function setShopperReference($value) {
    $this->setValue('shopperReference', $value);
  }

  /**
   * @return mixed
   */
  public function getShopperEmail() {
    return $this->shopperEmail;
  }

  public function setShopperEmail($value) {
    $this->setValue('shopperEmail', $value);
  }

  /**
   * @return mixed
   */
  public function getCountryCode() {
    return $this->countryCode;
  }

  public function setCountryCode($value) {
    $this->setValue('countryCode', $value);
  }

  /**
   * @return mixed
   */
  public function getMerchantReturnData() {
    return $this->merchantReturnData;
  }

  public function setMerchantReturnData($value) {
    $this->setValue('merchantReturnData', $value);
  }

  /**
   * @return mixed
   */
  public function getOrderData() {
    return $this->orderData;
  }

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
    return $this->signature != null && $this->skinCode != null && $this->merchantAccount != null;
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

  /**
   * @return array
   *
   * @throws InvalidStateException
   */
  public function getValues() {
    if(!$this->isReady()) {
      throw new InvalidStateException('Cannot get values for HPP (Host Payment pages).'.
        ' Payment has not filled all required fields');
    }
    $result = $this->getSignatureValues();

    // filter non null/undefined fields
    $result = array_filter($result,
      function($value) {
        return $value != null;
      }
    );

    $result['merchantSig'] = $this->signature;
    return $result;
  }

  /**
   * @param string $signature
   *
   * @return void
   */
  public function sign($signature) {
    $this->signature = $signature;
  }

  /**
   * @param string $signature
   *
   * @return void
   */
  public function signShopper($signature) {
    if(!$this->shopper) {
      throw new InvalidStateException("Can not sign missing shopper.");
    }
    $this->shopper->sign($signature);
  }

  /**
   * @return Shopper|null
   */
  public function getShopper() {
    return $this->shopper;
  }

  /**
   * @param string $signature
   *
   * @return void
   */
  public function signBillingAddress($signature) {
    if(!$this->billingAddress) {
      throw new InvalidStateException("Can not sign missing billing address.");
    }
    $this->billingAddress->sign($signature);
  }

  /**
   * @param string $signature
   *
   * @return void
   */
  public function signDeliveryAddress($signature) {
    if(!$this->deliveryAddress) {
      throw new InvalidStateException("Can not sign missing delivery address.");
    }
    $this->deliveryAddress->sign($signature);
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
  public function getSignatureValues() {
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
    if(Strings::endsWith($sessionValidity, '+00:00')) {
      $sessionValidity = Strings::replace($sessionValidity, '|\+00:00|', 'Z');
    }
    $values['sessionValidity'] = $sessionValidity;

    return $values;
  }

}
