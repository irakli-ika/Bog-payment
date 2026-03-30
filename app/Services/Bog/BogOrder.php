<?php

declare(strict_types=1);

namespace App\Services\Bog;

class BogOrder extends Payment
{
    public static function make(): static
    {
        return new static(app(BogApiClient::class));
    }

    public function withIdempotencyKey(string|object $key): static
    {
        $this->bogApiClient->withIdempotencyKey((string) $key);

        return $this;
    }

    public function process(): array
    {
        $response = $this->bogApiClient->post('/ecommerce/orders', $this->payload);

        $this->resetPayload();

        return [
            'id' => $response['id'],
            'redirect_url' => $response['_links']['redirect']['href'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }

    public function saveCard(): array
    {
        $response = $this->bogApiClient->post('/ecommerce/orders', $this->payload);

        $this->bogApiClient->put("/orders/{$response['id']}/cards");

        $this->resetPayload();

        return [
            'id' => $response['id'],
            'redirect_url' => $response['_links']['redirect']['href'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }

    public function chargeCard(string $parentOrderId): array
    {
        $response = $this->bogApiClient->post("/ecommerce/orders/{$parentOrderId}", $this->payload);

        $this->resetPayload();

        return [
            'id' => $response['id'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }

    public function subscription(): array
    {
        $response = $this->bogApiClient->post('/ecommerce/orders', $this->payload);

        $this->bogApiClient->put("/orders/{$response['id']}/subscriptions");

        $this->resetPayload();

        return [
            'id' => $response['id'],
            'redirect_url' => $response['_links']['redirect']['href'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }
}
