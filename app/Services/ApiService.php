<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ApiService
{
    private string $baseUrl;
    private string $webApiKey;

    public function __construct()
    {
        $this->baseUrl   = rtrim(config('services.api.url'), '/') . '/api';
        $this->webApiKey = config('services.firebase.web_api_key');
    }

    public function get(string $endpoint, array $query = []): array
    {
        return $this->handle(
            Http::withToken($this->token())->get($this->baseUrl . $endpoint, $query)
        );
    }

    public function post(string $endpoint, array $data = []): array
    {
        return $this->handle(
            Http::withToken($this->token())->post($this->baseUrl . $endpoint, $data)
        );
    }

    private function token(): string
    {
        if (now()->timestamp >= Session::get('admin_token_expiry', 0)) {
            $this->refreshToken();
        }

        return Session::get('admin_id_token');
    }

    private function refreshToken(): void
    {
        $response = Http::asForm()->post(
            'https://securetoken.googleapis.com/v1/token?key=' . $this->webApiKey,
            [
                'grant_type'    => 'refresh_token',
                'refresh_token' => Session::get('admin_refresh_token'),
            ]
        );

        if ($response->failed()) {
            $this->forceLogout();
        }

        $data = $response->json();

        Session::put([
            'admin_id_token'      => $data['id_token'],
            'admin_refresh_token' => $data['refresh_token'],
            'admin_token_expiry'  => now()->addMinutes(55)->timestamp,
        ]);
    }

    private function handle(Response $response): array
    {
        if ($response->status() === 401) {
            $this->forceLogout();
        }

        return $response->json() ?? [];
    }

    private function forceLogout(): never
    {
        Session::flush();
        throw new HttpResponseException(redirect()->route('admin.login'));
    }
}
