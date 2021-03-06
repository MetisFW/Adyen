<?php

namespace MetisFW\Adyen\Payment\HPP;

use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Object;

/**
 * @link https://docs.adyen.com/developers/hpp-manual#billingaddressandavs
 */
class Address extends Object {

  /** @var string */
  private $street;

  /** @var string */
  private $houseNumberOrName;

  /** @var string */
  private $city;

  /** @var string */
  private $postalCode;

  /** @var string */
  private $stateOrProvince;

  /** @var string */
  private $country;

  /** @var string */
  private $type;

  /** @var string */
  private $signature;

  /**
   * @return string
   */
  public function getStreet() {
    return $this->street;
  }

  /**
   * @param string $street
   */
  public function setStreet($street) {
    $this->setValue('street', $street);
  }

  /**
   * @return string
   */
  public function getHouseNumberOrName() {
    return $this->houseNumberOrName;
  }

  /**
   * @param string $houseNumberOrName
   */
  public function setHouseNumberOrName($houseNumberOrName) {
    $this->setValue('houseNumberOrName', $houseNumberOrName);
  }

  /**
   * @return string
   */
  public function getCity() {
    return $this->city;
  }

  /**
   * @param string $city
   */
  public function setCity($city) {
    $this->setValue('city', $city);
  }

  /**
   * @return string
   */
  public function getCountry() {
    return $this->country;
  }

  /**
   * @param string $country
   */
  public function setCountry($country) {
    $this->setValue('country', $country);
  }

  /**
   * @return string
   */
  public function getStateOrProvince() {
    return $this->stateOrProvince;
  }

  /**
   * @param string $stateOrProvince
   */
  public function setStateOrProvince($stateOrProvince) {
    $this->setValue('stateOrProvince', $stateOrProvince);
  }

  /**
   * @return string
   */
  public function getPostalCode() {
    return $this->postalCode;
  }

  /**
   * @param string $postalCode
   */
  public function setPostalCode($postalCode) {
    $this->setValue('postalCode', $postalCode);
  }

  /**
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @param string $type
   */
  public function setType($type) {
    $this->type = $type;
  }

  /**
   * @param string $signature
   *
   * @return void
   */
  public function sign($signature) {
    $this->signature = $signature;
  }

  private function setValue($propertyName, $value) {
    if($this->isSigned()) {
      throw new InvalidArgumentException("Cannot set property of already signed entity");
    }
    $this->$propertyName = $value;
  }

  public function isSigned() {
    return $this->signature != null;
  }

  /**
   * @return array
   */
  public function getSignatureValues() {
    $values = array(
      'street' => $this->street,
      'houseNumberOrName' => $this->houseNumberOrName,
      'city' => $this->city,
      'postalCode' => $this->postalCode,
      'stateOrProvince' => $this->stateOrProvince,
      'country' => $this->country
    );
    return $values;
  }

  public function isReady() {
    return $this->isSigned() && $this->street != null && $this->houseNumberOrName != null &&
    $this->city != null && $this->postalCode != null && $this->stateOrProvince != null &&
    $this->country != null && $this->type != null;
  }

  public function getValues() {
    if(!$this->isReady()) {
      throw new InvalidStateException('Cannot get values for HPP (Host Payment pages) address. '.
        'Payment address has not filled all required fields');
    }
    $result = $this->getSignatureValues();

    // filter non null/undefined fields
    $result = array_filter($result,
      function($value) {
        return $value != null;
      }
    );

    foreach($result as $key => $value) {
      $result['shopper.'.$key] = $value;
    }
    $result['shopperType'] = $this->getType();
    $result['shopperAddressSig'] = $this->signature;

    return $result;
  }

}
