# Unofficial BootPay package for Laravel

[![Total Downloads](https://poser.pugx.org/jinseokoh/bootpay/downloads)](https://packagist.org/packages/jinseokoh/bootpay)
[![License](https://poser.pugx.org/jinseokoh/bootpay/license)](https://packagist.org/packages/jinseokoh/bootpay)

Laravel 프로젝트에서 사용할 수 있는 [BootPay](https://www.bootpay.co.kr/) 용 PHP Laravel 패키지이다.

BootPay 에서 공식적으로 제공하는 PHP 용 [GitHub](https://github.com/bootpay/server_php) 리포가 있긴 하지만, 아쉽게도 PHP 디펜던시 관리자인 [Composer](https://getcomposer.org/)를 통하여 코드를 쉽게 추가할 수 없기에 BootPay 를 사용하려는 모던 PHP 개발자들을 위하여, 패키지화 하였다.

부트페이 결제는 JS Client SDK 로 수행하였고, 검증 및 취소만 이 패키지를 통해서 사용하였다. 이 패키지에서 제공하는 코드는 BootPay 공식코드와 다를 바가 없기때문에 모든 호출이 정상수행 되지 않을가 생각하지만, 검증 및 취소를 제외한 정기구독 등의 부가기능들은 사용한 바가 없으므로 혹시 문제가 발견된다면 이슈로 남겨주면 감사하겠다.

## 인스톨

이 패키지를 인스톨하기 위해서는 터미널에서 아래의 명령을 실행한다.

```
composer require jinseokoh/bootpay
```

## 사용법

Laravel 5.5 부터 제공하는 Package Auto-Discovery 기능으로, 이 패키지를 인스톨함과 동시에 ServiceProvider 와 Facade 등록이 자동으로 된다.

### 설정

Laravel 프로젝트 `.env` 파일에 BootPay 에 연관된 아래의 3개 Key/Value 값을 추가해야만 한다. BootPay 관리자 페이지에 가면, REST API 용 Application Id 와 Private Key 를 부여받을 수 있다.

```
BOOTPAY_URI=https://api.bootpay.co.kr
BOOTPAY_REST_APP_ID=000000000000000000000000
BOOTPAY_PRIVATE_KEY=0000000000000000000000000000000000000000000=
```

### 예제

BootPay 메뉴얼을 보면, API 호출에 필요한 AccessToken 은 30분간만 유효하다는 내용이 있다. 이 패키지의 핸들러 내부에는 AccessToken 을 25분 동안만 캐쉬 레이어에 저장시켜 사용하다가 해당시간이 초과되면 새로운 AccessToken 을 재요청하여 사용하는 로직이 들어 있다. 따라서, 이 패키지 사용자는 AccessToken 관리에 필요한 로직을 추가할 필요가 없고, 단지 verify() 또는 cancel() 등의 원하는 API 호출만 수행하면 된다.

일반적인 WebApp 플로우는 BootPay JS 코드로 결제수행 후 `OrderId` 와 `ReceiptId` 를 인자값으로 Laravel API 을 호출하여, 실제요금과 같은지 확인하는 과정을 거치게 된다. 이때 사용하는 API 가 verify() 이며 Controller 에서, `JinseokOh\BootPay\BootPayHanlder` 클래스를 DI 를 이용해 주입하거나, BootPay facade 를 이용하여 생성 후, 아래의 샘플처럼 사용한다. 

#### verify() 호출 샘플코드

```php
    public function verify(
        OrderVerifyRequest $request,
        BootPayHandler $bootPayHandler,
        OrderHandler $orderHandler
    ): JsonResponse {
        $orderId = $request->getOrderId();
        $receiptId = $request->getReceiptId();
        $order = $orderHandler->findById($orderId);

        try {
            $response = $bootPayHandler->verify($receiptId);
        } catch (\Throwable $e) {
            \Log::critical("[LOG] {$e->getMessage()}");
            throw $e;
        }
        :
        // 추가로직
        :
    }
```

특정 결제에 대하여 취소를 하는 경우, 아래의 cancel() API 룰 호출한다. 

#### cancel() 호출 샘플코드

```php
    public function cancel(
        OrderCancelRequest $request,
        BootPayHandler $bootPayHandler,
        OrderHandler $orderHandler
    ): JsonResponse {
        $orderId = $request->getOrderId();
        $receiptId = $request->getReceiptId();
        $order = $orderHandler->findById($orderId);

        try {
            $response = $bootPayHandler->cancel($receiptId);
        } catch (\Throwable $e) {
            \Log::critical("[LOG] {$e->getMessage()}");
            throw $e;
        }
        :
        // 추가로직
        :
    }
```

## License

The MIT Licence (MIT).
