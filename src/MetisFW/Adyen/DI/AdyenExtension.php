<?php

namespace MetisFW\Adyen\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;

class AdyenExtension extends CompilerExtension {

  private $defaults = array(
    'acceptUnsignedNotifications' => false,
    'gaTrackingEnabled' => true,
    'defaultPaymentParameters' => array()
  );

  public function loadConfiguration() {
    $builder = $this->getContainerBuilder();
    $config = $this->getConfig($this->defaults);

    Validators::assertField($config, 'live');
    Validators::assertField($config, 'skinCode');
    Validators::assertField($config, 'merchantAccount');
    Validators::assertField($config, 'hmacKey');
    Validators::assertField($config, 'defaultPaymentParameters', 'array');
    Validators::assertField($config, 'gaTrackingEnabled', 'bool');

    $contextArguments = array(
      $config['skinCode'],
      $config['merchantAccount'],
      $config['hmacKey']
    );
    $contextName = $this->prefix('AdyenContext');
    $builder->addDefinition($contextName)
      ->setClass('MetisFW\Adyen\AdyenContext', $contextArguments)
      ->addSetup('setDefaultPaymentParameters', array($config['defaultPaymentParameters']))
      ->addSetup('setHPPEndpoint', array($config['hppEndpoint']))
      ->addSetup('setLive', array($config['live']))
      ->addSetup('setAcceptUnsignedNotifications', array($config['acceptUnsignedNotifications']))
      ->addSetup('setGaTrackingEnabled', array($config['gaTrackingEnabled']));

    $builder->addDefinition($this->prefix('basicPaymentOperationFactory'))
      ->setImplement('MetisFW\Adyen\Payment\HPP\BasicPaymentOperationFactory')
      ->setArguments(array($contextName));

    $builder->addDefinition($this->prefix('simplePaymentOperationFactory'))
      ->setImplement('MetisFW\Adyen\Payment\HPP\SimplePaymentOperationFactory')
      ->setArguments(array($contextName));

    $builder->addDefinition($this->prefix('paymentControlFactory'))
      ->setImplement('MetisFW\Adyen\UI\HPP\PaymentControlFactory');

    $builder->addDefinition($this->prefix('basicPaymentNotificationOperationFactory'))
      ->setImplement('MetisFW\Adyen\Payment\Notification\BasicNotificationOperationFactory')
      ->setArguments(array($contextName));
  }

}
