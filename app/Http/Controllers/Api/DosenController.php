<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function create(Request $request) {
        $request->validate([
            'nip' => 'required',
            'name' => 'required',
            'dob' => 'required',
            'email' => 'required|email',
        ]);
        
        try {
            User::create([
                'id_number' => $request->nip,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'dosen'
            ]);
    
            $dosen = Dosen::create([
                'nip' => $request->nip,
                'name' => $request->name,
                'dob' => $request->dob,
                'email' => $request->email,
            ]);

            return $this->responseOk($dosen, 'Dosen berhasil ditambahkan');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }
}
