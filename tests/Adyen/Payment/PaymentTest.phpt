<?php

namespace MetisFWTests\Adyen\Payment;

use MetisFW\Adyen\Payment\Payment;
use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__.'/../../bootstrap.php';

class PaymentTest extends TestCase {

  public function testSigning() {
    $payment = new Payment();
    $payment->setMerchantReference('SKINTEST-1435226439255');
    $payment->setCurrencyCode('EUR');
    $payment->setPaymentAmount(199);
    $payment->setSessionValidity(DateTime::createFromFormat(DateTime::ISO8601, '2015-06-25T10:31:06Z'));
    $payment->setShipBeforeDate(DateTime::createFromFormat('Y-m-d', '2015-07-01'));
    $payment->setShopperLocale('en_GB');

    $skinCode        = "X7hsNDWp";
    $merchantAccount = "TestMerchant";
    $hmacKey         = "4468D9782DEF54FCD706C9100C71EC43932B1EBC2ACF6BA0560C05AAA7550C48";
    $payment->sign($hmacKey, $skinCode, $merchantAccount);

    Assert::true($payment->isSigned());
  }

  /**
   * @throws \Nette\InvalidArgumentException
   */
  public function testSetAfterSigning() {
    $payment = new Payment();
    $payment->setMerchantReference('SKINTEST-1435226439255');
    $payment->setCurrencyCode('EUR');
    $payment->setPaymentAmount(199);
    $payment->setSessionValidity(DateTime::createFromFormat(DateTime::ISO8601, '2015-06-25T10:31:06Z'));
    $payment->setShipBeforeDate(DateTime::createFromFormat('Y-m-d', '2015-07-01'));
    $payment->setShopperLocale('en_GB');

    $skinCode        = "X7hsNDWp";
    $merchantAccount = "TestMerchant";
    $hmacKey         = "4468D9782DEF54FCD706C9100C71EC43932B1EBC2ACF6BA0560C05AAA7550C48";
    $payment->sign($hmacKey, $skinCode, $merchantAccount);

    Assert::true($payment->isSigned());
    $payment->setMerchantReference("New value");
  }

  public function testSetCurrencyCode() {
    $payment = new Payment();
    $payment->setCurrencyCode("EUR");

    Assert::equal($payment->getCurrencyCode(), "EUR");
  }

  /*
  /**
   * @throws \Nette\InvalidArgumentException
   */
  //public function testSetCurrencyCodeInvalid() {
  //  $payment = new Payment();
  //  $payment->setCurrencyCode("invalid");
  //}

  /**
   * Test coming from example. see link
   *
   * @link https://docs.adyen.com/manuals/hpp-manual/hpp-hmac-calculation/hmac-payment-setup-sha-256
   */
  public function testHPP() {
    $payment = new Payment();
    $payment->setMerchantReference('SKINTEST-1435226439255');
    $payment->setCurrencyCode('EUR');
    $payment->setPaymentAmount(199);
    $payment->setSessionValidity(DateTime::createFromFormat(DateTime::ISO8601, '2015-06-25T10:31:06Z'));
    $payment->setShipBeforeDate(DateTime::createFromFormat('Y-m-d', '2015-07-01'));
    $payment->setShopperLocale('en_GB');

    $skinCode        = "X7hsNDWp";
    $merchantAccount = "TestMerchant";
    $hmacKey         = "4468D9782DEF54FCD706C9100C71EC43932B1EBC2ACF6BA0560C05AAA7550C48";
    $payment->sign($hmacKey, $skinCode, $merchantAccount);

    $hppValues = $payment->getHPPValues();

    Assert::equal('GJ1asjR5VmkvihDJxCd8yE2DGYOKwWwJCBiV3R51NFg=', $hppValues['merchantSig']);
  }

  /**
   * @throws \Nette\InvalidStateException
   */
  public function testNotFullFilled() {
    $payment = new Payment();
    $payment->setMerchantReference('SKINTEST-1435226439255');
    $payment->setPaymentAmount(199);
    $payment->setSessionValidity(DateTime::createFromFormat(DateTime::ISO8601, '2015-06-25T10:31:06Z'));
    $payment->setShipBeforeDate(DateTime::createFromFormat('Y-m-d', '2015-07-01'));
    $payment->setShopperLocale('en_GB');

    $skinCode        = "X7hsNDWp";
    $merchantAccount = "TestMerchant";
    $hmacKey         = "4468D9782DEF54FCD706C9100C71EC43932B1EBC2ACF6BA0560C05AAA7550C48";
    $payment->sign($hmacKey, $skinCode, $merchantAccount);

    $payment->getHPPValues();
  }

}

\run(new PaymentTest());
