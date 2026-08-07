<?php

namespace App\Services;

use Google\Client;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class GoogleService
{
    public static function verifyAccessToken($token): false|array
    {
        try {
            if (empty($token)) {
                return false;
            }

            $provider = Socialite::driver('google')->stateless();

            $tokenResponse = $provider->getAccessTokenResponse($token);

            $googleUser = $provider->userFromToken($tokenResponse['access_token']);

            return $googleUser->user;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
