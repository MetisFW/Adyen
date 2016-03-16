<?php

namespace MetisFWTests\Adyen\DI;

use MetisFW\Adyen\DI\AdyenExtension;
use Nette\Configurator;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__.'/../../bootstrap.php';

class PayPalExtensionTest extends TestCase {

  public function testExtensionCreated() {
    $config = new Configurator();
    $config->setTempDirectory(TEMP_DIR);
    $config->addParameters(array('container' => array('class' => 'SystemContainer_'.md5(TEMP_DIR))));
    AdyenExtension::register($config);
    $config->addConfig(__DIR__.'/../../adyen.config.neon');

    $container = $config->createContainer();
    $context = $container->getByType('MetisFW\Adyen\AdyenContext');

    Assert::notEqual(null, $context);
  }

  /*
  public function testMultipleInstances() {
    $config = new Configurator();
    $config->setTempDirectory(TEMP_DIR);
    $config->addParameters(array('container' => array('class' => 'SystemContainer_'.md5(TEMP_DIR))));
    AdyenExtension::register($config);
    $config->addConfig(__DIR__.'/../../adyen.config.neon');

    AdyenExtension::register($config);
    $config->addConfig(__DIR__.'/../../adyen.config.neon');

    $container = $config->createContainer();
    $paypal = $container->getService('adyen.AdyenContext');

    Assert::notEqual(null, $paypal);
  }
  */
}

\run(new PayPalExtensionTest());
