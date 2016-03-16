# MetisFW/Adyen

## Setup

1) Register extension
```
extensions:
  adyen: MetisFW\Adyen\DI\AdyenExtension
```

2) Set up extension parameters

```neon
adyen:
  live: false
  skinCode: aevNpEyW
  merchantAccount: GrowJOBSroCOM
  hmacKey: 84519D921DBC7F30A6A05C788A966E2EB725AF8D9BF44360D80BE397037E138E
  hppEndpoint: select
  gaTracking: false # default value is true 
  acceptUnsignedNotifications: true # default value is false
  defaultPaymentParameters:
    currencyCode: EUR
```

##Usage

#### [HPP (Hosted Payment Pages)](https://github.com/MetisFW/Adyen/blob/master/docs/en/hpp.md)

#### [Notifications](https://github.com/MetisFW/Adyen/blob/master/docs/en/notification.md)
