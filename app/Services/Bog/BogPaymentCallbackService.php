<?php

namespace App\Services\Bog;

use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BogPaymentCallbackService
{
    public function verifySignature(string $data, string $signature, string $publicKey): bool
    {
        try {
            $decodedSignature = base64_decode($signature);

            $publicKey = openssl_pkey_get_public($publicKey);

            $verified = openssl_verify($data, $decodedSignature, $publicKey, 'RSA-SHA256');

            return $verified === 1;
        } catch (Exception $e) {
            Log::error('Verification exception: '.$e->getMessage());
            throw $e;
        }
    }

    public function signitureIsValid(string $body, string $signature, string $publicKey)
    {
        if (! $this->verifySignature($body, $signature, $publicKey)) {
            throw new HttpException(401, 'Invalid Signature');
        }

    }
}
