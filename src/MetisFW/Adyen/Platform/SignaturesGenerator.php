<?php

namespace MetisFW\Adyen\Platform;

use MetisFW\Adyen\AdyenContext;
use MetisFW\Adyen\Payment\HPP\Payment;
use MetisFW\Adyen\Payment\HPP\PaymentResult;
use MetisFW\Adyen\Payment\Notification\NotificationRequestItem;
use MetisFW\Adyen\Payment\Platform\Address;
use MetisFW\Adyen\Payment\Platform\Shopper;
use Nette\Object;

class SignaturesGenerator extends Object {

  /** @var AdyenContext */
  private $secret;

  /**
   * @param string $secret
   */
  public function __construct($secret) {
    $this->secret = $secret;
  }

  /**
   * @param Payment $payment
   *
   * @return string
   */
  public function generatePaymentSignature(Payment $payment) {
    $values = $payment->getSignatureValues();
    $values = $this->filterValues($values);
    return $this->generateSignature($values);

  }

  /**
   * @param PaymentResult $paymentResult
   *
   * @return string
   */
  public function generatePaymentResultSignature(PaymentResult $paymentResult) {
    $values = $paymentResult->getSignatureValues();
    $values = $this->filterValues($values);
    return $this->generateSignature($values);
  }

  /**
   * @param Address $address
   *
   * @return string
   */
  public function generateAddressSignature(Address $address) {
    $values = $address->getSignatureValues();
    $values = $this->filterValues($values);
    return $this->generateSignature($values);
  }

  /**
   * @param Shopper $shopper
   *
   * @return string
   */
  public function generateShopperSignature(Shopper $shopper) {
    $values = $shopper->getSignatureValues();
    $values = $this->filterValues($values);
    return $this->generateSignature($values);
  }

  /**
   * @param NotificationRequestItem $notificationItem
   * @return string
   */
  public function generateNotificationSignature(NotificationRequestItem $notificationItem) {
    $data = $notificationItem->getSignatureString();
    echo $data . "\n";
    return $this->signData($data);
  }

  /**
   * @param array $values
   * @return mixed
   */
  private function generateSignature(array $values) {
    //helper function
    $escapeValues = function($val) {
      return str_replace(':', '\\:', str_replace('\\', '\\\\', $val));
    };

    // Sort the array by key using SORT_STRING order
    ksort($values, SORT_STRING);

    // Generate the signing data string
    $data = implode(":",
      array_map($escapeValues,
        array_merge(array_keys($values), array_values($values))
      )
    );
    return $this->signData($data);
  }

  private function filterValues($values) {
    // filter non null/undefined fields
    $result = array_filter($values,
      function ($value) {
        return $value != null;
      }
    );

    return $result;
  }

  /**
   * @param $signData
   * @return mixed
   */
  private function signData($signData) {
// base64-encode the binary result of the HMAC computation
    $merchantSig = base64_encode(hash_hmac('sha256', $signData, pack("H*", $this->secret), true));
    return $merchantSig;
  }

}
