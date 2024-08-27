<?php

namespace App\Services;

// use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class ApiFootballService
{

    public function makeRequest(string $endpoint, array $params) 
    {

        $url = config('services.api-football.base_uri') . $endpoint;

        try {
            $response = Http::withHeaders([
                'x-rapidapi-key' => config('services.api-football.key'),
                'Accept' => 'application/json',
            ])->get($url, $params);

            return json_decode($response->getBody());
        } catch (\Exception $e) {
            Log::error('API request failed: ' . $e->getMessage());
            return null;
        }
    }
}