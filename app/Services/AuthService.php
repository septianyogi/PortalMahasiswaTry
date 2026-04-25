<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{
    public function login(array $data)
    {
        if(!$token = auth('api')->attempt($data))
            {
                throw new \Exception('Invalid email or password', 401);
            }
        
        $user = auth('api')->user();
        $token = $this->generateTokens($user);
        return $token;
    }

    private function generateTokens($user)
    {
        return [
            'access_token' => $this->createAccessToken($user),
            'refresh_token' => $this->createRefreshToken($user),
            'token_type' => 'bearer',
        ];
    }

    private function createAccessToken($user)
    {
        return JWTAuth::claims([
            'jti' => Str::uuid()->toString(),
            'exp' => now()->addMinutes(30)->timestamp,
        ])->fromUser($user);
    }

    private function createRefreshToken($user)
    {
        $jti = Str::uuid()->toString();

        DB::table('refresh_tokens')->insert([
            'user_id' => $user->id,
            'jti' => $jti,
            'expires_at' => now()->addDays(7),
        ]);

        return $jti;
    }

    public function refresh($refreshToken)
    {
        $token = DB::table('refresh_tokens')
            ->where('jti', $refreshToken)
            ->first();
        
        if(!$token || now()->gt($token->expires_at)) {
            throw new \Exception('Invalid or expired refresh token', 401);
        }
        $user = User::findOrFail($token->user_id);

        return [
            'access_token' => $this->createAccessToken($user),
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
        ];
    }

    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'] ?? 'student',
        ]);
        return $user;
    }

    public function logout($refreshToken)
    {
        DB::table('refresh_tokens')
        ->when('jti', $refreshToken)
        ->delete();

        return true;
    }
}
