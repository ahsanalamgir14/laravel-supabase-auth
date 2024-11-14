<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Request;

class SupabaseAuthService
{
    protected $client;
    protected $authUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->authUrl = env('SUPABASE_URL') . '/auth/v1/';
        $this->apiKey = env('SUPABASE_KEY');
        $this->client = new Client([
            'base_uri' => $this->authUrl,
            'headers' => [
                'apikey' => $this->apiKey,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function registerUser($email, $password)
    {
        try {
            $response = $this->client->post('signup', [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function loginUser($email, $password)
    {
        try {
            $response = $this->client->post('token', [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                    'grant_type' => 'password',
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function verifyEmail($token)
    {
        try {
            // Verify the token with Supabase API using the correct endpoint
            $response = $this->client->post('verify', [
                'json' => [
                    'access_token' => $token,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            // Handle the response
            if (isset($data['user'])) {
                return response()->json([
                    'message' => 'Email verified successfully!',
                    'user' => $data['user'],
                ]);
            }

            return response()->json([
                'error' => 'Invalid token or token expired.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
