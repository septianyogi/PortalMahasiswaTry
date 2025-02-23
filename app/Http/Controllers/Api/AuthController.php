<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

        if($user || Hash::check($request->password, $user->password)){
            $role = $user->role;
            
            $token = $user->createtoken('user login')->plainTextToken;

            $student = Student::where('npm', $user->id_number)->first();
            $dosen = Dosen::where('nip', $user->id_number)->first();

            return $this->responseOk($this->respondWithToken($user, $student, $dosen, $role, $token), 'login success');
        } else {
            return $this->responseError('email or password incorrect', );
        }
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }

        
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin'
            ]);

            return $this->responseOk($user, 'register success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }


    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return $this->responseOk(null, 'logout success');
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
