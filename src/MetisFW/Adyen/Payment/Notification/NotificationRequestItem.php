<?php

namespace MetisFW\Adyen\Payment\Notification;

use Nette\InvalidArgumentException;
use Nette\Object;
use Nette\Utils\DateTime;

class NotificationRequestItem extends Object {

  /** @var Notification */
  private $parent;

  /** @var \stdClass */
  private $additionalData;

  /** @var int */
  private $amountValue;

  /** @var string */
  private $amountCurrency;

  /** @var string */
  private $pspReference;

  /** @var string */
  private $eventCode;

  /** @var DateTime */
  private $eventDate;

  /** @var string */
  private $merchantAccountCode;

  /** @var array */
  private $operations;

  /** @var string */
  private $merchantReference;

  /** @var string */
  private $originalReference;

  /** @var string */
  private $paymentMethod;

  /** @var string */
  private $reason;

  /** @var bool */
  private $success;

  /**
   * @param Notification $parent
   */
  public function __construct(Notification $parent) {
    $this->parent = $parent;
  }

  /**
   * @return Notification
   */
  public function getParent() {
    return $this->parent;
  }

  /**
   * @return string
   */
  public function getAdditionalData() {
    return $this->additionalData;
  }

  /**
   * @param \stdClass $additionalData
   */
  public function setAdditionalData(\stdClass $additionalData) {
    $this->additionalData = $additionalData;
  }

  /**
   * @return int
   */
  public function getAmountValue() {
    return $this->amountValue;
  }

  /**
   * @param int $amountValue
   */
  public function setAmountValue($amountValue) {
    $this->amountValue = $amountValue;
  }

  /**
   * @return string
   */
  public function getAmountCurrency() {
    return $this->amountCurrency;
  }

  /**
   * @param string $amountCurrency
   */
  public function setAmountCurrency($amountCurrency) {
    $this->amountCurrency = $amountCurrency;
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
  public function getEventCode() {
    return $this->eventCode;
  }

  /**
   * @param string $eventCode
   */
  public function setEventCode($eventCode) {
    $this->eventCode = $eventCode;
  }

  /**
   * @return DateTime
   */
  public function getEventDate() {
    return $this->eventDate;
  }

  /**
   * @param DateTime $eventDate
   */
  public function setEventDate(DateTime $eventDate) {
    $this->eventDate = $eventDate;
  }

  /**
   * @return string
   */
  public function getMerchantAccountCode() {
    return $this->merchantAccountCode;
  }

  /**
   * @param string $merchantAccountCode
   */
  public function setMerchantAccountCode($merchantAccountCode) {
    $this->merchantAccountCode = $merchantAccountCode;
  }

  /**
   * @return array
   */
  public function getOperations() {
    return $this->operations;
  }

  /**
   * @param array $operations
   */
  public function setOperations($operations) {
    $this->operations = $operations;
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
  public function getOriginalReference() {
    return $this->originalReference;
  }

  /**
   * @param string $originalReference
   */
  public function setOriginalReference($originalReference) {
    $this->originalReference = $originalReference;
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
  public function getReason() {
    return $this->reason;
  }

  /**
   * @param string $reason
   */
  public function setReason($reason) {
    $this->reason = $reason;
  }

  /**
   * @return bool
   */
  public function isSuccess() {
    return $this->success;
  }

  /**
   * @param bool $success
   *
   * @return void
   */
  public function setSuccess($success) {
    if(!is_bool($success)) {
      throw new InvalidArgumentException('Invalid type for success property. Expected boolean, but '.
        gettype($success).' given.');
    }

    $this->success = $success;
  }

  /**
   * @return bool
   */
  public function isAuthorised() {
    return $this->eventCode === 'AUTHORISATION' && $this->isSuccess();
  }

  /**
   * @return bool
   */
  public function isCancelled() {
    return ($this->eventCode === 'CANCELLATION' || $this->eventCode === 'CANCEL_OR_REFUND') && $this->isSuccess();
  }

  /**
   * @return bool
   */
  public function isRefund() {
    return ($this->eventCode === 'REFUND' || $this->eventCode === 'CANCEL_OR_REFUND') && $this->isSuccess();
  }

  public function getSignatureString() {
    $result = $this->getPspReference().':';
    $result .= $this->getOriginalReference().':';
    $result .= $this->getMerchantAccountCode().':';
    $result .= ($this->getMerchantReference() ? $this->getMerchantReference() : '').':';
    $result .= $this->getAmountValue().':';
    $result .= $this->getAmountCurrency().':';
    $result .= $this->getEventCode().':';
    $result .= ($this->isSuccess() ? 'true' : 'false');

    return $result;
  }

}
