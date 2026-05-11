<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Dosen;
use App\Models\Student;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request){
        try {
            $data = $request->validated();
            $login = $this->authService->login($data);
            return $this->responseOk($login, 'Login Success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), 401);
        }
        
    }

    public function register(RegisterRequest $request) {
        try {
            $data = $request->validated();
            $user = $this->authService->register($data);
            return $this->responseOk($user, 'register success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), 400);
        }
    }

    public function refresh(Request $request)
    {
        try {
             $request->validate([
            'refresh_token' => 'required'
            ]);
            $refreshToken = $request->refresh_token;

            $result = $this->authService->refresh($refreshToken);
            return $this->responseOk($result, 'Token refreshed', 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 401);
        }
    }


    public function logout(Request $request){
        try {
            $this->authService->logout($request->refresh_token);

            return $this->responseOk(null, 'logout success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), 400);
        }   
    }

    protected function respondWithToken($user, $student, $dosen, $role, $token)
    {
        return [

            'user' => $user,
            'student' => $student,
            'dosen' => $dosen,
            'role' => $role,
            'token' => $token
        ];
    }
}
