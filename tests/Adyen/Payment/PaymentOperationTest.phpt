<?php

namespace MetisFWTests\Adyen\Payment;

use MetisFW\Adyen\AdyenContext;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__.'/../../bootstrap.php';
require_once __DIR__.'/DummyPaymentOperation.php';

class PaymentOperationTest extends TestCase {

  /** @var array */
  private $config;

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
      'hmacKey' => 'hmacKey'
    );
  }

  public function testGetPayment() {
    $operation = $this->createDummyPaymentOperation();
    $payment = $operation->getPayment();
    Assert::equal('12345678', $payment->getMerchantReference());
    Assert::equal('EUR', $payment->getCurrencyCode());

    Assert::true($payment->isSigned());
  }

  private function createDummyPaymentOperation() {
    $context = new AdyenContext($this->config['skinCode'], $this->config['merchantAccount'], $this->config['hmacKey']);
    $context->setDefaultPaymentParameters(array('currencyCode' => 'EUR'));
    $operation = new DummyPaymentOperation($context);

    return $operation;
  }

}

\run(new PaymentOperationTest());
