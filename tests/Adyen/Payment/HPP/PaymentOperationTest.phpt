<?php

namespace MetisFWTests\Adyen\Payment\HPP;

use MetisFW\Adyen\AdyenContext;
use MetisFW\Adyen\Payment\HPP\PaymentResult;
use MetisFW\Adyen\Platform\SignaturesGenerator;
use Nette\Application\Request;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__.'/../../../bootstrap.php';
require_once __DIR__.'/DummyPaymentOperation.php';

class PaymentOperationTest extends TestCase {

  /** @var array */
  private $config;

  /** @var AdyenContext */
  private $adyen;

  /** @var DummyPaymentOperation */
  private $operation;

  /**
   * This method is called before a test is executed.
   *
   * @return void
   */
  protected function setUp() {
    parent::setUp();
    $this->config = array(
      'skinCode' => '4aD37dJA',
      'merchantAccount' => 'TestMerchant',
      'hmacKey' => '84519D921DBC7F30A6A05C788A966E2EB725AF8D9BF44360D80BE397037E138E'
    );

    $this->adyen = new AdyenContext($this->config['skinCode'], $this->config['merchantAccount'], $this->config['hmacKey']);
    $this->adyen->setDefaultPaymentParameters(array('currencyCode' => 'EUR'));
    $this->operation = new DummyPaymentOperation($this->adyen);
  }

  public function testGetPayment() {
    $operation = $this->operation;
    $payment = $operation->getPayment();
    Assert::equal('12345678', $payment->getMerchantReference());
    Assert::equal('EUR', $payment->getCurrencyCode());

    Assert::false($payment->isSigned());
  }

  public function testSignPayment() {
    $operation = $this->operation;
    $payment = $operation->getPayment();
    $payment = $operation->signPayment($payment);

    Assert::equal($this->config['skinCode'], $payment->getSkinCode());
    Assert::equal($this->config['merchantAccount'], $payment->getMerchantAccount());

    $generator = new SignaturesGenerator($this->config['hmacKey']);
    $expectedSignature = $generator->generatePaymentSignature($payment);
    Assert::equal($expectedSignature, $payment->getValues()['merchantSig']);

    Assert::true($payment->isSigned());
  }

  public function testResURLGaTracking() {
    $operation = $this->operation;
    $payment = $operation->getPayment();
    $payment->setResURL('http://www.example.com');

    $this->adyen->setGaTrackingEnabled(true);
    $payment = $operation->signPayment($payment);

    Assert::equal('http://www.example.com/?utm_nooverride=1', $payment->getResURL());
  }

  public function testResURLNoGaTracking() {
    $operation = $this->operation;
    $payment = $operation->getPayment();
    $payment->setResURL('http://www.example.com');

    $this->adyen->setGaTrackingEnabled(false);
    $payment = $operation->signPayment($payment);

    Assert::equal('http://www.example.com', $payment->getResURL());
  }

  public function testHandleReturnSuccess() {
    $operation = $this->operation;

    $onReturnCallsCount = 0;
    $operation->onReturn[] = function (DummyPaymentOperation $this, PaymentResult $paymentResult)
    use (&$onReturnCallsCount) {
      $onReturnCallsCount++;
    };

    $onCancelCallsCount = 0;
    $operation->onCancel[] = function (DummyPaymentOperation $this, PaymentResult $paymentResult)
    use (&$onCancelCallsCount) {
      $onCancelCallsCount++;
    };

    $request = new Request('no-name', 'no-method', array(
      'authResult' => 'AUTHORISED',
      'merchantReference' => '5d1df90c2848f50cea79f398c8515bb9',
      'merchantSig' => 'UAvo9p4/SnkAJrL1cTV0GhvUPbq3qymH1Xq3I53MjXM=',
      'paymentMethod' => 'mc',
      'pspReference' => '8814582187239667',
      'shopperLocale' => 'en_GB',
      'skinCode' => 'aevNpEyW'
    ));
    $operation->handleReturn($request);

    Assert::equal(1, $onReturnCallsCount);
    Assert::equal(0, $onCancelCallsCount);
  }

  public function testHandleReturnError() {
    $operation = $this->operation;

    $onReturnCallsCount = 0;
    $operation->onReturn[] = function (DummyPaymentOperation $this, PaymentResult $paymentResult)
    use (&$onReturnCallsCount) {
      $onReturnCallsCount++;
    };

    $onCancelCallsCount = 0;
    $operation->onCancel[] = function (DummyPaymentOperation $this, PaymentResult $paymentResult)
    use (&$onCancelCallsCount) {
      $onCancelCallsCount++;
    };

    $request = new Request('no-name', 'no-method', array(
      'authResult' => 'CANCELLED',
      'merchantReference' => '5d1df90c2848f50cea79f398c8515bb9',
      'merchantSig' => 'xiuEehJLPgA9EYUh4ZG+yGgFXmUm7XpYEnZnVVhSRMo=',
      'paymentMethod' => 'mc',
      'pspReference' => '8814582187239667',
      'shopperLocale' => 'en_GB',
      'skinCode' => 'aevNpEyW'
    ));
    $operation->handleReturn($request);

    Assert::equal(0, $onReturnCallsCount);
    Assert::equal(1, $onCancelCallsCount);
  }

}

\run(new PaymentOperationTest());
