<?php

namespace JinseokOh\BootPay\Test;

use JinseokOh\BootPay\BootPayFacade;
use JinseokOh\BootPay\BootPayHandler;

class BootPayHandlerTest extends TestCase
{
    public function testClientApplicationIdIsNotNull()
    {
        $applicationId = \BootPay::getClient()->getApplicationId();

        $this->assertNotNull($applicationId);
    }

    public function testClientPrivateKeyIsNotNull()
    {
        $privateKey = \BootPay::getClient()->getPrivateKey();

        $this->assertNotNull($privateKey);
    }

    public function testClientAccessTokenIsNull()
    {
        $accessToken = \BootPay::getClient()->getAccessToken();

        $this->assertNull($accessToken);
    }

    public function clientAccessTokenIsNotNull()
    {
        \BootPay::setAccessToken();
        $accessToken = \BootPay::getClient()->getAccessToken();

        $this->assertNotNull($accessToken);
    }
}
