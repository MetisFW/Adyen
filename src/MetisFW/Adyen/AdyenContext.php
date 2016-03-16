<?php

namespace MetisFW\Adyen;

use Nette\InvalidArgumentException;
use Nette\Object;

class AdyenContext extends Object {

  /** @var string */
  private $skinCode;

  /** @var string */
  private $merchantAccount;

  /** @var string */
  private $hmacKey;

  /** @var array */
  private $defaultPaymentParameters = array();

  /** @var string */
  private $HPPEndpoint;

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

  public function setDefaultPaymentParameters(array $parameters) {
    $this->defaultPaymentParameters = $parameters;
  }

  public function getDefaultPaymentParameters() {
    return $this->defaultPaymentParameters;
  }

  public function setHPPEndpoint($endpoint) {
    $allowedValues = array_keys($this->HPPEndpointMapping);
    if (!array_key_exists($endpoint, $allowedValues)) {
      throw new InvalidArgumentException('Invalid endpoint for HPP (Hosted Payment Pages).'.
        ' Possible values: \'' . implode(', ', $allowedValues) . '\' but ' . $endpoint . ' given.');
    }

    $this->HPPEndpoint = $endpoint;
  }

  public function getHPPEndpointUrl() {
    return $this->HPPEndpoint[$this->HPPEndpoint];
  }

}
