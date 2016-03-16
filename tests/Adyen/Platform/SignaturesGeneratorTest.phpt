<?php

namespace MetisFWTests\Adyen\Platform;

use MetisFW\Adyen\Payment\Notification\Notification;
use MetisFW\Adyen\Payment\HPP\Payment;
use MetisFW\Adyen\Payment\HPP\PaymentResult;
use MetisFW\Adyen\Platform\SignaturesGenerator;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__.'/../../bootstrap.php';

class SignaturesGeneratorTest extends TestCase {

  public function testPaymentSignature() {
    $payment = new Payment();
    $payment->setMerchantReference('5015693b40fcf7345dc01131bb57ec2d');
    $payment->setCurrencyCode('EUR');
    $payment->setPaymentAmount(200);
    $payment->setSessionValidity(DateTime::createFromFormat(DateTime::ISO8601, '2016-03-17T13:24:55+01:00'));
    $payment->setResURL('http://localhost/procrastination/events/registration-5015693b40fcf7345dc01131bb57ec2d?do=adyenPaymentButton-return');
    $payment->setShipBeforeDate(DateTime::createFromFormat('Y-m-d', '2016-03-17'));

    $payment->setSkinCode("aevNpEyW");
    $payment->setMerchantAccount("GrowJOBSroCOM");

    $hmacKey = "84519D921DBC7F30A6A05C788A966E2EB725AF8D9BF44360D80BE397037E138E";
    $generator = new SignaturesGenerator($hmacKey);

    $expectedSignature = 'FZrgNdfl5IGxaXGnuJ1Y4PIHl/e93KxzoQ8jF8Gxoc4=';
    $signature = $generator->generatePaymentSignature($payment);
    Assert::equal($expectedSignature, $signature);

    $payment->sign($signature);
    $hppValues = $payment->getValues();
    Assert::equal($expectedSignature, $hppValues['merchantSig']);
  }

  public function testPaymentSignatureDocumentationSample() {
    $payment = new Payment();
    $payment->setMerchantReference('SKINTEST-1435226439255');
    $payment->setCurrencyCode('EUR');
    $payment->setPaymentAmount(199);
    $payment->setSessionValidity(DateTime::createFromFormat(DateTime::ISO8601, '2015-06-25T10:31:06Z'));
    $payment->setShipBeforeDate(DateTime::createFromFormat('Y-m-d', '2015-07-01'));
    $payment->setShopperLocale('en_GB');

    $payment->setSkinCode("X7hsNDWp");
    $payment->setMerchantAccount("TestMerchant");
    $hmacKey = "4468D9782DEF54FCD706C9100C71EC43932B1EBC2ACF6BA0560C05AAA7550C48";

    $generator = new SignaturesGenerator($hmacKey);
    $signature = $generator->generatePaymentSignature($payment);
    $payment->sign($signature);

    $hppValues = $payment->getValues();

    Assert::equal('GJ1asjR5VmkvihDJxCd8yE2DGYOKwWwJCBiV3R51NFg=', $hppValues['merchantSig']);
  }

  public function testPaymentResultSignature() {
    $secret = '84519D921DBC7F30A6A05C788A966E2EB725AF8D9BF44360D80BE397037E138E';
    $generator = new SignaturesGenerator($secret);
    $paymentResult = new PaymentResult();
    $paymentResult->setAuthResult('AUTHORISED');
    $paymentResult->setPspReference('7914581480057116');
    $paymentResult->setSkinCode('aevNpEyW');
    $paymentResult->setMerchantReference('ef2cd80b58b101dd2bc695940a98c92f');
    $paymentResult->setMerchantReturnData('');
    $paymentResult->setPaymentMethod('mc');
    $paymentResult->setShopperLocale('en_GB');

    $signature = $generator->generatePaymentResultSignature($paymentResult);
    Assert::equal('26Dq3h361s+QjyVI8N9wrCifYA8cHnoAkZPIqzSXRGE=', $signature);
  }

  /**
   * @link https://docs.adyen.com/manuals/api-manual#enablinghmaconnotifications
   */
  public function testNotificationSignatureJavaDocumentationSample() {
    $secret = '009E9E92268087AAD241638D3325201AFC8AAE6F3DCD369B6D32E87129FFAB10';
    $generator = new SignaturesGenerator($secret);

    $rawBody = '
      {  
         "live":"false",
         "notificationItems":[  
            {  
               "notificationRequestItem":{  
                  "additionalData":{
                     "hmacSignature":"S+5bAYKLd+L2A07Pal0pG/qBarnInaIe709YNzNcHOA="
                  },
                  "amount":{  
                     "value":8650,
                     "currency":"EUR"
                  },
                  "pspReference":"7914073251449896",
                  "eventCode":"AUTHORISATION",
                  "eventDate":"2014-08-06T17:15:34.121+02:00",
                  "merchantAccountCode":"TestMerchant",
                  "operations":[  
                     "CANCEL",
                     "CAPTURE",
                     "REFUND"
                  ],
                  "merchantReference":"TestPayment-1407325143704",
                  "paymentMethod":"visa",
                  "success":"true"
               }
            }
         ]
      }
    ';
    $request = new Request(new UrlScript('adyen-test'),
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      function () use ($rawBody) {
        return $rawBody;
      });

    $notification = Notification::createFromRequest($request);
    $actual = $generator->generateNotificationSignature($notification->getNotificationItems()[0]);

    Assert::equal('S+5bAYKLd+L2A07Pal0pG/qBarnInaIe709YNzNcHOA=', $actual);
  }

}

\run(new SignaturesGeneratorTest());
