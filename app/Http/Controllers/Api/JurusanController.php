<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function create(Request $request) {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
        ]);

        try {
            $jurusan = Jurusan::create([
                'code' => $request->code,
                'name' => $request->name,
            ]);

            return $this->responseOk($jurusan, 'Jurusan berhasil ditambahkan');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function destroy($id) {
        try {
            $jurusan = Jurusan::find($id);
            $jurusan->delete();

            return $this->responseOk($jurusan, 'Jurusan berhasil dihapus');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }
}
