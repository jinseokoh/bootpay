<?php

namespace JinseokOh\BootPay\Test;

use JinseokOh\BootPay\BootPayHandler;

class BootPayHandlerTest extends TestCase
{
    public function testClientApplicationIdIsNotNull()
    {
        $handler = app(BootPayHandler::class);
        $applicationId = $handler->getClient()->getApplicationId();

        $this->assertNotNull($applicationId);
    }

    public function testClientPrivateKeyIsNotNull()
    {
        $handler = app(BootPayHandler::class);
        $privateKey = $handler->getClient()->getPrivateKey();

        $this->assertNotNull($privateKey);
    }

    /**
     * this test works only if your BootPay credentials are provided correctly
     */
    public function ClientAccessTokenIsNull()
    {
        $handler = app(BootPayHandler::class);
        $handler->setAccessToken();
        $accessToken = $handler->getClient()->getAccessToken();

        $this->assertNotNull($accessToken);
    }
}
