<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FakultasController extends Controller
{
    public function create(Request $request) {
        try {
            $fakultas = DB::transaction(function () use ($request) {
                return Fakultas::create(attributes: [
                    'code' => $request->code,
                    'name' => $request->name,
                ]);
            });

            return $this->responseOk($fakultas, 'Fakultas berhasil ditambahkan', 201);
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function destroy($id) {
        try {
            $fakultas = Fakultas::find($id);
            $fakultas->delete();

            return $this->responseOk($fakultas, 'Fakultas berhasil dihapus');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }
}
