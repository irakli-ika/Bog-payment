<?php

namespace App\Services\Bog;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BogApiClient
{
    private string $clientId = '';

    private string $secretKey = '';

    private string $baseUrl = '';

    private ?string $accessToken = null;

    private ?string $idempotencyKey = null;

    public function __construct()
    {
        $this->clientId = config('bog_api.client_id');
        $this->secretKey = config('bog_api.secret_key');
        $this->baseUrl = rtrim(config('bog_api.base_url'), '/');
    }

    public function authenticate(): void
    {
        try {
            $authUrl = 'https://oauth2.bog.ge/auth/realms/bog/protocol/openid-connect/token';

            $response = Http::asForm()->withBasicAuth($this->clientId, $this->secretKey)->post($authUrl, ['grant_type' => 'client_credentials']);

            if ($response->successful()) {
                $this->accessToken = $response->json()['access_token'];
            } else {
                Log::error('BOG Authentication Failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new Exception('Authentication is failed with BOG');
            }

        } catch (Exception $e) {
            Log::error('Authentication Exception: '.$e->getMessage());
            throw $e;
        }
    }

    private function serviceIsAuthenticated(): void
    {
        if (empty($this->accessToken)) {
            $this->authenticate();
        }
    }

    private function handleResponse(object $response): mixed
    {
        if ($response->successful()) {
            return $response->json();
        }

        Log::error('API Request Failed', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        throw new Exception('API request failed with Bank of Georgia.'.$response->body());
    }

    public function withIdempotencyKey(string $key): self
    {
        $this->idempotencyKey = $key;

        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        if (! in_array($name, ['get', 'post', 'put', 'delete'])) {
            throw new Exception('Method not allowed');
        }
        $endpoint = $arguments[0];
        $payload = $arguments[1] ?? [];

        $this->serviceIsAuthenticated();

        try {
            $url = $this->baseUrl.$endpoint;

            $request = Http::withToken($this->accessToken)
                ->asJson();

            if ($this->idempotencyKey) {
                $request = $request->withHeaders([
                    'Idempotency-Key' => $this->idempotencyKey,
                ]);
            }

            $response = $request->{$name}($url, $payload);

            $this->idempotencyKey = null;

            return $this->handleResponse($response);

        } catch (Exception $e) {
            Log::error(strtoupper($name).' Request Exception: '.$e->getMessage());
            throw $e;
        }
    }
}
