<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testSuccessful()
    {
        $user = User::whereEmail('xlnt83@gmail.com')->first();
        $this->actingAs($user, 'api');
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer ' . $token;

        $this->json('GET', 'api/home', $header)
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                    'google_id',
                    'avatar',
                    'avatar_original',
                    'lon',
                    'lat',
                ],
                [
                    'temp',
                    'feels_like',
                    'temp_min',
                    'temp_max',
                    'pressure',
                    'humidity'
                ]
            ]);
    }

    public function testUnauthenticated()
    {
        $this->json('GET', 'api/home', ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'message' => "Unauthenticated."
            ]);
    }
}

