<?php

namespace JinseokOh\BootPay\Test;

use JinseokOh\BootPay\BootPayFacade;
use JinseokOh\BootPay\BootPayServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('cache:clear');
    }

    /**
     * Define environment setup.
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('bootpay.base_uri', 'https://dev-api.bootpay.co.kr');
        $app['config']->set('bootpay.app_id', 'fake-bootpay-app-id');
        $app['config']->set('bootpay.private_key', 'fake-bootpay-private-key');
    }

    /**
     * Load package service provider
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            BootPayServiceProvider::class,
        ];
    }
    /**
     * Load package alias
     * @return array
     */
    protected function getPackageAliases($app): array
    {
        return [
            'BootPay' => BootPayFacade::class,
        ];
    }
}
