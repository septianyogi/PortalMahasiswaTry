<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckBlacklistToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            
            $jti = $payload->get('jti');

            $isBlacklisted = DB::table('blacklisted_tokens')
                ->where('jti', $jti)
                ->exists();
            
            if($isBlacklisted) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Token Revoked'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized'
            ], 401);
        }   

        return $next($request);
    }
}
