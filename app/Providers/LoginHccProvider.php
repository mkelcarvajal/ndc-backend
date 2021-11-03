<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\User;
use App\Token;
use Illuminate\Support\Str;

class LoginHccProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        $user = User::with('token', 'usuarios')->where('id', $identifier)->first();
        if(!$user)
        {
            return null;
        } else {
            Token::firstOrCreate(
                ['id' => $identifier],
                ['remember_token' => "123456"]
            );
            return $user;
        }
    }

    public function retrieveByToken($identifier, $token)
    {

    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        Token::updateOrCreate(
            ['nombre' => $user->nombre],

            ['id' => $user->id],
            ['remember_token' => $token]
        );
    }

    public function retrieveByCredentials(array $credentials)
    {

    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }
}