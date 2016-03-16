<?php

namespace MetisFWTests\Adyen\Payment\HPP;

use MetisFW\Adyen\Payment\HPP\Payment;
use MetisFW\Adyen\Platform\SignaturesGenerator;
use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__.'/../../../bootstrap.php';

class PaymentTest extends TestCase {

  public function testSigning() {
    $payment = new Payment();
    $payment->setMerchantReference('SKINTEST-1435226439255');
    $payment->setCurrencyCode('EUR');
    $payment->setPaymentAmount(199);
    $payment->setSessionValidity(DateTime::createFromFormat(DateTime::ISO8601, '2015-06-25T10:31:06Z'));
    $payment->setShipBeforeDate(DateTime::createFromFormat('Y-m-d', '2015-07-01'));
    $payment->setShopperLocale('en_GB');

    $payment->setSkinCode("X7hsNDWp");
    $payment->setMerchantAccount("TestMerchant");
    $signature = "signature";
    $payment->sign($signature);

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

    $payment->setSkinCode("X7hsNDWp");
    $payment->setMerchantAccount("TestMerchant");
    $signature = "signature";
    $payment->sign($signature);

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
  public function testValues() {
    $payment = new Payment();
    $payment->setMerchantReference('SKINTEST-1435226439255');
    $payment->setCurrencyCode('EUR');
    $payment->setPaymentAmount(199);
    $payment->setSessionValidity(DateTime::createFromFormat(DateTime::ISO8601, '2015-06-25T10:31:06Z'));
    $payment->setShipBeforeDate(DateTime::createFromFormat('Y-m-d', '2015-07-01'));
    $payment->setShopperLocale('en_GB');

    $payment->setSkinCode("X7hsNDWp");
    $payment->setMerchantAccount("TestMerchant");
    $secret = "4468D9782DEF54FCD706C9100C71EC43932B1EBC2ACF6BA0560C05AAA7550C48";
    $generator = new SignaturesGenerator($secret);
    $signature = $generator->generatePaymentSignature($payment);
    $payment->sign($signature);

    $payment->sign($signature);

    $values = $payment->getValues();

    Assert::equal('GJ1asjR5VmkvihDJxCd8yE2DGYOKwWwJCBiV3R51NFg=', $values['merchantSig']);
    Assert::count(9, $values);
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

    $payment->setSkinCode("X7hsNDWp");
    $payment->setMerchantAccount("TestMerchant");
    $secret = "4468D9782DEF54FCD706C9100C71EC43932B1EBC2ACF6BA0560C05AAA7550C48";
    $generator = new SignaturesGenerator($secret);
    $signature = $generator->generatePaymentSignature($payment);
    $payment->sign($signature);

    $payment->getValues();
  }

}

\run(new PaymentTest());
