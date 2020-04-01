<?php

namespace JinseokOh\BootPay;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Cache;

class BootPayClient
{
    const ACCESS_TOKEN_KEY = 'bootpay-access-token';

    private $client;
    private $applicationId;
    private $privateKey;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => config('bootpay.base_uri'),
        ]);
        $this->applicationId = config('bootpay.app_id');
        $this->privateKey = config('bootpay.private_key');
    }

    /**
     * @return string
     */
    public function getApplicationId(): string
    {
        return $this->applicationId;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return Cache::get(self::ACCESS_TOKEN_KEY);
    }

    /**
     * @param string $token
     */
    public function setAccessToken(string $token): void
    {
        Cache::put(self::ACCESS_TOKEN_KEY, $token, 60 * 25); // valid for 25 mins
    }

    /**
     * @param string $uri
     * @param array $body
     * @param string $method
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function call(string $uri, array $body, string $method = 'POST')
    {
        return $this->client->request($method, $uri, [
            'headers' => $this->getDefaultHeaders([
                'Authorization' => $this->getAccessToken()
            ]),
            'json' => $body,
        ]);
    }

    // ================================================================================
    // private methods
    // ================================================================================

    /**
     * @param array $headers
     * @return array
     */
    private function getDefaultHeaders(array $headers = []): array
    {
        return array_merge([
            'Content-Type' => 'application/json; charset=UTF-8',
            'Accept' => 'application/json',
        ], $headers);
    }
}
