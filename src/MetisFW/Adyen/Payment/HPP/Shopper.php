<?php

namespace MetisFW\Adyen\Payment\HPP;

use Nette\InvalidArgumentException;
use Nette\Object;

/**
 * @link https://docs.adyen.com/developers/hpp-manual#shopperinformation
 */
class Shopper extends Object {

  /** @var string */
  private $firstName;

  /** @var string */
  private $infix;

  /** @var string */
  private $lastName;

  /** @var string */
  private $gender;

  /** @var string */
  private $dateOfBirthDayOfMonth;

  /** @var string */
  private $dateOfBirthMonth;

  /** @var string */
  private $dateOfBirthYear;

  /** @var string */
  private $stateOrProvince;

  /** @var string */
  private $country;

  /** @var string */
  private $telephoneNumber;

  /** @var string */
  private $type;

  /** @var string */
  private $signature;

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
   * @return string
   */
  public function getTelephoneNumber() {
    return $this->telephoneNumber;
  }

  /**
   * @param string $telephoneNumber
   */
  public function setTelephoneNumber($telephoneNumber) {
    $this->setValue('telephoneNumber', $telephoneNumber);
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
  public function getDateOfBirthYear() {
    return $this->dateOfBirthYear;
  }

  /**
   * @param string $dateOfBirthYear
   */
  public function setDateOfBirthYear($dateOfBirthYear) {
    $this->setValue('dateOfBirthYear', $dateOfBirthYear);
  }

  /**
   * @return string
   */
  public function getDateOfBirthMonth() {
    return $this->dateOfBirthMonth;
  }

  /**
   * @param string $dateOfBirthMonth
   */
  public function setDateOfBirthMonth($dateOfBirthMonth) {
    $this->setValue('dateOfBirthMonth', $dateOfBirthMonth);
  }

  /**
   * @return string
   */
  public function getDateOfBirthDayOfMonth() {
    return $this->dateOfBirthDayOfMonth;
  }

  /**
   * @param string $dateOfBirthDayOfMonth
   */
  public function setDateOfBirthDayOfMonth($dateOfBirthDayOfMonth) {
    $this->setValue('dateOfBirthDayOfMonth', $dateOfBirthDayOfMonth);
  }

  /**
   * @return string
   */
  public function getGender() {
    return $this->gender;
  }

  /**
   * @param string $gender
   */
  public function setGender($gender) {
    $this->setValue('gender', $gender);
  }

  /**
   * @return string
   */
  public function getInfix() {
    return $this->infix;
  }

  /**
   * @param string $infix
   */
  public function setInfix($infix) {
    $this->setValue('infix', $infix);
  }

  /**
   * @return string
   */
  public function getLastName() {
    return $this->lastName;
  }

  /**
   * @param string $lastName
   */
  public function setLastName($lastName) {
    $this->setValue('lastName', $lastName);
  }

  /**
   * @return string
   */
  public function getFirstName() {
    return $this->firstName;
  }

  /**
   * @param string $firstName
   */
  public function setFirstName($firstName) {
    $this->setValue('firstName', $firstName);
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
      'firstName' => $this->firstName,
      'infix' => $this->infix,
      'lastName' => $this->lastName,
      'gender' => $this->gender,
      'dateOfBirthDayOfMonth' => $this->dateOfBirthDayOfMonth,
      'dateOfBirthMonth' => $this->dateOfBirthMonth,
      'dateOfBirthYear' => $this->dateOfBirthYear,
      'telephoneNumber' => $this->telephoneNumber
    );
    return $values;
  }

}
