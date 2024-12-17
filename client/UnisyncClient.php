<?php

namespace Client;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UnisyncClient
{
    protected $baseUrl;
    protected $secretKey;

    public function __construct()
    {
        $this->baseUrl = config('services.unisync.base_url', 'https://unisync.alphagames.my.id/api');
        $this->secretKey = config('services.unisync.secret_key', 'ABC123xyz');
    }

    public function submitAssessment(): array
    {
        $nonce = Str::random(16);
        $timestamp = now()->timestamp * 1000;

        $signature = hash('sha256', $nonce . $timestamp . $this->secretKey);

        $response = Http::withHeaders([
            'X-Nonce' => $nonce,
            'X-API-Signature' => $signature,
        ])->post("{$this->baseUrl}/assessment", [
            'timestamp' => $timestamp,
        ]);

        return $response->json();
    }
}
