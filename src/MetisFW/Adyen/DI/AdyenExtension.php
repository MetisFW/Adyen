<?php

namespace MetisFW\Adyen\DI;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;

class AdyenExtension extends CompilerExtension {

  public function loadConfiguration() {
    $builder = $this->getContainerBuilder();
    $config = $this->getConfig();

    Validators::assertField($config, 'skinCode');
    Validators::assertField($config, 'merchantAccount');
    Validators::assertField($config, 'hmacKey');
    Validators::assertField($config, 'defaultPaymentParameters', 'array');
    Validators::assertField($config['defaultPaymentParameters'], 'merchantReference');

    $contextArguments = array(
      $config['skinCode'],
      $config['merchantAccount'],
      $config['hmacKey']
    );
    $builder->addDefinition($this->prefix('Adyen'))
      ->setClass('MetisFW\Adyen\AdyenContext', $contextArguments)
      ->addSetup('setDefaultPaymentParameters', array($config['defaultPaymentParameters']))
      ->addSetup('setHostedPaymentPageOption', array($config['hostedPaymentPage']));

    $builder->addDefinition($this->prefix('basicPaymentOperationFactory'))
      ->setImplement('MetisFW\Adyen\Payment\BasicPaymentOperationFactory');
  }

  /**
   * @param Configurator $configurator
   *
   * @return void
   */
  public static function register(Configurator $configurator) {
    $configurator->onCompile[] = function ($config, Compiler $compiler) {
      $compiler->addExtension('adyen', new AdyenExtension());
    };
  }

}
