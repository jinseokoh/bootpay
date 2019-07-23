<?php

namespace JinseokOh\BootPay;

class BootPayHandler
{
    private $client;

    /**
     * Constructor
     */
    public function __construct(BootPayClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return BootPayClient
     */
    public function getClient(): BootPayClient
    {
        return $this->client;
    }

    /**
     * Request Access Token
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setAccessToken(): void
    {

        $response = $this->client
            ->call('/request/token', [
                'application_id' => $this->client->getApplicationId(),
                'private_key' => $this->client->getPrivateKey(),
            ]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode((string) $response->getBody(), false);
            $this->client->setAccessToken($data->data->token);
        }
    }

    /**
     * Verify Payment
     *
     * @param string $receiptId
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verify(
        string $receiptId
    ): ?object {
        if (! $this->client->getAccessToken()) {
            $this->setAccessToken();
        }

        $response = $this->client
            ->call("/receipt/{$receiptId}", [], 'GET');

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * Cancel Payment
     *
     * @param string $receiptId
     * @param int|null $price
     * @param string|null $name
     * @param string|null $reason
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancel(
        string $receiptId,
        ?int $price = null,
        ?string $name = null,
        ?string $reason = null
    ): ?object {
        if (! $this->client->getAccessToken()) {
            $this->setAccessToken();
        }

        $response = $this->client
            ->call('/cancel', [
                'receipt_id' => $receiptId,
                'price' => $price,
                'name' => $name,
                'reason' => $reason,
            ]);

        return json_decode((string) $response->getBody(), false);
    }

    // ================================================================================
    // The following public methods are included for the sake of completeness.
    // Use at your own risk.
    // ================================================================================

    /**
     * @param array $data
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function subscribeCardBilling(array $data): ?object
    {
        if (! $this->client->getAccessToken()) {
            $this->setAccessToken();
        }

        $response = $this->client
            ->call('/subscribe/billing.json', $data);

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * @param array $data
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function subscribeCardBillingReserve(array $data): ?object
    {
        if (! $this->client->getAccessToken()) {
            $this->setAccessToken();
        }

        $response = $this->client
            ->call('/subscribe/billing/reserve.json', $data);

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * @param string $reserveId
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function subscribeCardBillingReserveCancel(string $reserveId): ?object
    {
        if (! $this->client->getAccessToken()) {
            $this->setAccessToken();
        }

        $response = $this->client
            ->call("/subscribe/billing/reserve/{$reserveId}", [], 'DELETE');

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * @param array $data
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSubscribeBillingKey(array $data): ?object
    {
        if (! $this->client->getAccessToken()) {
            $this->setAccessToken();
        }

        $response = $this->client
            ->call('/request/card_rebill.json', $data);

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * @param string $billingKey
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function destroySubscribeBillingKey(string $billingKey): ?object
    {
        if (! $this->client->getAccessToken()) {
            $this->setAccessToken();
        }

        $response = $this->client
            ->call("/subscribe/billing/{$billingKey}.json", $data, 'DELETE');

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * @param string $receiptId
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submit(string $receiptId): ?object
    {
        if (! $this->client->getAccessToken()) {
            $this->setAccessToken();
        }

        $response = $this->client
            ->call('/submit.json', [ 'receipt_id' => $receiptId ]);

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * @param array $data
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function startDelivery(array $data): ?object
    {
        if (! $this->client->getAccessToken()) {
            $this->setAccessToken();
        }

        $response = $this->client
            ->call("/delivery/start/{$data['receipt_id']}.json", $data, 'PUT');

        return json_decode((string) $response->getBody(), false);
    }
}
