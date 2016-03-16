<?php

namespace MetisFWTests\Adyen\DI;

use MetisFW\Adyen\AdyenContext;
use MetisFW\Adyen\DI\AdyenExtension;
use Nette\Configurator;
use Nette\DI\Compiler;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__.'/../../bootstrap.php';

class AdyenExtensionTest extends TestCase {

  public function testExtensionCreated() {
    $config = new Configurator();
    $config->setTempDirectory(TEMP_DIR);
    $config->addParameters(array('container' => array('class' => 'SystemContainer_'.md5(uniqid()))));

    $config->onCompile[] = function ($sender, Compiler $compiler) {
      $compiler->addExtension('adyen', new AdyenExtension());
    };

    $config->addConfig(__DIR__.'/../../adyen.config.neon');

    $container = $config->createContainer();
    /** @var AdyenContext $context */
    $context = $container->getByType('MetisFW\Adyen\AdyenContext');

    Assert::notEqual(null, $context);

    Assert::false($context->isLive());
    Assert::equal('aevNpEyW', $context->getSkinCode());
    Assert::equal('GrowJOBSroCOM', $context->getMerchantAccount());
    Assert::equal('84519D921DBC7F30A6A05C788A966E2EB725AF8D9BF44360D80BE397037E138E', $context->getHmacKey());

    Assert::equal('EUR', $context->getDefaultPaymentParameters()['currencyCode']);
    Assert::equal('https://test.adyen.com/hpp/select.shtml', $context->getHPPEndpointUrl());

    Assert::false($context->acceptUnsignedNotifications());
  }

  public function testMultipleInstances() {
    $config = new Configurator();
    $config->setTempDirectory(TEMP_DIR);
    $config->addParameters(array('container' => array('class' => 'SystemContainer_'.md5(uniqid()))));

    $config->onCompile[] = function ($sender, Compiler $compiler) {
      $compiler->addExtension('adyen1', new AdyenExtension());
      $compiler->addExtension('adyen2', new AdyenExtension());
    };

    $config->addConfig(__DIR__.'/adyen-multiple.config.neon');

    $container = $config->createContainer();
    $context1 = $container->getService('adyen1.AdyenContext');
    $context2 = $container->getService('adyen2.AdyenContext');

    Assert::notEqual(null, $context1);
    Assert::notEqual(null, $context2);
  }

}

\run(new AdyenExtensionTest());
