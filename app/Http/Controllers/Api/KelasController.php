<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    public function getClassByDosen() {
        try {
            $user = Auth::user();
            $classes = Kelas::with('dosen')->where('dosen_id', $user->id_number)->get();

            return $this->responseOk($classes, 'Data kelas berhasil diambil');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function getAllClass() {
        try {
            $classes = Kelas::with('dosen')->get();

            return $this->responseOk($classes, 'Data kelas berhasil diambil');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }


    public function create(Request $request) {
        $request->validate([
            'jurusan_id' => 'required',
            'name' => 'required',
            'date' => 'required',
            'time' => 'required',
            'dosen_id' => 'required',
            'quota' => 'required',
            'room' => 'required',
            'semester' => 'required',
        ]);

        try {
            $kelas = Kelas::create([
                'code' => Carbon::now()->format('dmy').'-'.$request->jurusan_id.'-'.Carbon::now()->format('Hi'),
                'jurusan_id' => $request->jurusan_id,
                'name' => $request->name,
                'date' => $request->date,
                'time' => $request->time,
                'dosen_id' => $request->dosen_id,
                'quota' => $request->quota,
                'room' => $request->room,
                'semester' => $request->semester,
            ]);

            return $this->responseOk($kelas, 'Kelas has been created');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function update(Request $request, $id) {
        try {
            $kelas = Kelas::find($id);
            $kelas->update([
                'jurusan_id' => $request->jurusan_id,
                'name' => $request->name,
                'date' => $request->date,
                'time' => $request->time,
                'dosen' => $request->dosen,
                'quota' => $request->quota,
                'room' => $request->room,
                'semester' => $request->semester,
            ]);

            return $this->responseOk($kelas, 'Kelas has been updated');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }

    public function destroy($id) {
        try {
            $kelas = Kelas::find($id);
            $kelas->delete();

            return $this->responseOk($kelas, 'Kelas deleted');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode());
        }
    }
}
