<?php

declare(strict_types=1);

namespace App\Traits;

trait PayloadBuilder
{
    protected array $payload;

    // protected bool $saveCard = false;

    public function resetPayload(?array $data = null): void
    {
        $this->payload = $data ?? [
            'callback_url' => config('bog_api.callback_url'),
            'redirect_urls' => config('bog_api.redirect_urls'),
            'purchase_units' => [
                'currency' => 'GEL',
            ],
        ];
    }

    public function externalOrderId(int $id): static
    {
        $this->payload['external_order_id'] = $id;

        return $this;
    }

    public function totalAmount(int|float $amount): static
    {
        $this->payload['purchase_units']['total_amount'] = is_int($amount) ? price_from_cent($amount) : $amount;

        return $this;
    }

    public function basket(array $basket = []): static
    {
        $this->payload['purchase_units']['basket'] = [$basket];

        return $this;
    }

    public function unitPrice(int $price = 1): static
    {
        $this->payload['purchase_units']['basket']['unit_price'] = $price;

        return $this;
    }

    public function productId(int $id): static
    {
        $this->payload['purchase_units']['basket']['product_id'] = $id;

        return $this;
    }

    public function quantity(int $quantity = 1): static
    {
        $this->payload['purchase_units']['basket']['quantity'] = $quantity;

        return $this;
    }

    public function currency(string $currency = 'GEL'): static
    {
        $this->payload['purchase_units']['currency'] = $currency;

        return $this;
    }

    // public function saveCard(): static
    // {
    //     $this->saveCard = true;

    //     return $this;
    // }

    public function redirectUrls(string $success, string $fail): static
    {
        $this->payload['redirect_urls'] = [
            'success' => $success,
            'fail' => $fail,
        ];

        return $this;
    }
}
