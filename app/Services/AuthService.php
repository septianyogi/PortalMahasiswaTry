<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            
            throw new \Exception('Invalid email or password', 401);
        }

        $role = $user->role;

        // ambil relasi berdasarkan role
        $student = null;
        $dosen = null;

        if ($role === 'student') {
            $student = Student::where('user_id', $user->id)->first();
        }

        if ($role === 'dosen') {
            $dosen = Dosen::where('user_id', $user->id)->first();
        }
        
        $token = $this->generateTokens($user);


        return [
        'user' => $user,
        'student' => $student,
        'dosen' => $dosen,
        'role' => $role,
        'token' => $token,
    ];
    }

    private function generateTokens($user)
    {
        return [
            'access_token' => $this->createAccessToken($user),
            'refresh_token' => $this->createRefreshToken($user),
            'token_type' => 'bearer',
            'expires_in' =>  config('jwt.ttl') * 60,
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

        if (!$token) {
            throw new \Exception('Invalid refresh token', 401);
        }

        if (Carbon::now()->gt($token->expires_at)) {
            // hapus kalau expired
            DB::table('refresh_tokens')->where('jti', $refreshToken)->delete();
            throw new \Exception('Refresh token expired', 401);
        }

        $user = User::findOrFail($token->user_id);

        return [
            'access_token' => $this->createAccessToken($user),
            'refresh_token' => $refreshToken, // tetap sama
            'token_type' => 'bearer',
            'expires_in' => 60 * 30,
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
