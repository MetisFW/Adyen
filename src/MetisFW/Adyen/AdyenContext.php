<?php

namespace MetisFW\Adyen;

use MetisFW\Adyen\Platform\SignaturesGenerator;
use Nette\InvalidArgumentException;
use Nette\Object;

class AdyenContext extends Object {

  /** @var string */
  private $skinCode;

  /** @var string */
  private $merchantAccount;

  /** @var string */
  private $hmacKey;

  /** @var bool */
  private $live;

  /** @var bool */
  private $acceptUnsignedNotifications;

  /** @var SignaturesGenerator */
  private $signatureGenerator;

  /** @var array */
  private $defaultPaymentParameters = array();

  /** @var string */
  private $HPPEndpoint;

  /** @var bool */
  private $gaTrackingEnabled;

  /** @var array */
  private $HPPEndpointMapping = array(
    'select' => 'https://test.adyen.com/hpp/select.shtml',
    'pay' => 'https://test.adyen.com/hpp/pay.shtml',
    'skipDetails' => 'https://test.adyen.com/hpp/skipDetails.shtml',
    'directory' => 'https://test.adyen.com/hpp/directory.shtml'
  );

  /**
   * @param string $skinCode
   * @param string $merchantAccount
   * @param string $hmacKey
   */
  public function __construct($skinCode, $merchantAccount, $hmacKey) {
    $this->skinCode = $skinCode;
    $this->merchantAccount = $merchantAccount;
    $this->hmacKey = $hmacKey;
    $this->signatureGenerator = new SignaturesGenerator($hmacKey);
  }

  public function getSkinCode() {
    return $this->skinCode;
  }

  public function getMerchantAccount() {
    return $this->merchantAccount;
  }

  public function getHmacKey() {
    return $this->hmacKey;
  }

  public function getSignaturesGenerator() {
    return $this->signatureGenerator;
  }

  public function setDefaultPaymentParameters(array $parameters) {
    $this->defaultPaymentParameters = $parameters;
  }

  public function getDefaultPaymentParameters() {
    return $this->defaultPaymentParameters;
  }

  public function setHPPEndpoint($endpoint) {
    if(!$this->isHppEndpointValid($endpoint)) {
      $allowedValues = array_keys($this->HPPEndpointMapping);
      throw new InvalidArgumentException('Invalid endpoint for HPP (Hosted Payment Pages).'.
        ' Possible values: \''.implode(', ', $allowedValues).'\' but '.$endpoint.' given.');
    }

    $this->HPPEndpoint = $endpoint;
  }

  public function isHppEndpointValid($endpoint) {
    return array_key_exists($endpoint, $this->HPPEndpointMapping);
  }

  public function getHPPEndpointUrl($endpoint = null) {
    if(!$endpoint) {
      $endpoint = $this->HPPEndpoint;
    }
    return $this->HPPEndpointMapping[$endpoint];
  }

  public function setLive($value) {
    $this->live = $value;
  }

  public function isLive() {
    return $this->live;
  }

  public function setAcceptUnsignedNotifications($value) {
    $this->acceptUnsignedNotifications = $value;
  }

  public function acceptUnsignedNotifications() {
    return $this->acceptUnsignedNotifications;
  }

  public function isGaTrackingEnabled() {
    return $this->gaTrackingEnabled;
  }

  public function setGaTrackingEnabled($gaTrackingEnabled) {
    $this->gaTrackingEnabled = $gaTrackingEnabled;
  }

}
