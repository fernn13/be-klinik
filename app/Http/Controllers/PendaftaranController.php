<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PendaftaranController extends Controller
{
    public function data(Request $request)
    {
        //dd($request->all());
        try {
            $vaData = DB::table('pasien_pendaftaran')
                ->select('id', 'no_rm', 'nama', 'tempat_lahir', 'tgl_lahir', 'jns_kelamin', 'alamat', 'no_tlp', 'pendidikan', 'pekerjaan', 'no_ktp', 'no_asuransi', 'jns_asuransi')
                ->orderByDesc('created_at')
                ->get();

            return response()->json([
                'status' => self::$status['SUKSES'],
                'message' => 'SUKSES',
                'data' => $vaData,
                'datetime' => date('Y-m-d H:i:s')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'Terjadi Kesalahan Saat Proses Data',
                'datetime' => date('Y-m-d H:i:s')
            ], 400);
        }
    }

    private function generateNomorRM()
    {
        $lastRM = Pendaftaran::orderBy('no_rm', 'desc')->first();

        if (!$lastRM) {
            return 'RM0001';
        }

        // Ambil angka dari no_rm terakhir
        $number = intval(substr($lastRM->no_rm, 2));
        $nextNumber = $number + 1;

        // Format ulang dengan leading zero
        return 'RM' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        try {
            // Generate no_rm otomatis
            $no_rm = $this->generateNomorRM();

            // Tambahkan no_rm ke request data
            $data = $request->all();
            $data['no_rm'] = $no_rm;
            $data['tgl_lahir'] = Carbon::parse($request->tgl_lahir)->format('Y-m-d');

            // Validasi data (tanpa validasi unique karena no_rm sudah dijamin unik)
            $vaValidator = Validator::make($data, [
                'no_rm' => 'required|unique:pasien_pendaftaran,no_rm',
                'nama' => 'required|string|max:100',
                'tempat_lahir' => 'required|string|max:100',
                'tgl_lahir' => 'required|date',
                'jns_kelamin' => 'required|in:Laki-laki,Perempuan',
                'alamat' => 'required|string',
                'no_tlp' => 'required|string|max:20',
                'pendidikan' => 'nullable|string',
                'pekerjaan' => 'nullable|string',
                'no_ktp' => 'nullable|string|max:20',
                'no_asuransi' => 'nullable|string|max:30',
                'jns_asuransi' => 'nullable|string|max:50'
            ]);

            if ($vaValidator->fails()) {
                return response()->json([
                    'status' => self::$status['BAD_REQUEST'],
                    'message' => $vaValidator->errors()->first(),
                    'datetime' => now()
                ], 422);
            }

            Pendaftaran::create($data);

            return response()->json([
                'status' => self::$status['SUKSES'],
                'message' => 'Data pasien berhasil disimpan',
                'datetime' => now()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'Terjadi kesalahan saat simpan data: ' . $th->getMessage(),
                'datetime' => now()
            ], 400);
        }
    }

    public function show(Request $request)
    {
        $pendaftaran = Pendaftaran::find($request->id);
        if (!$pendaftaran) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json(['data' => $pendaftaran], 200);
    }


    public function update(Request $request)
    {
        $pasien = Pendaftaran::find($request->id);
        if (!$pasien) {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }

        $pasien->update($request->all());

        return response()->json(['message' => 'Data pasien berhasil diupdate']);
    }


    public function delete(Request $request)
    {
        try {
            $vaValidator = Validator::make($request->all(), [
                'id' => 'required|exists:pasien_pendaftaran,id',
            ], [
                'required' => 'Kolom :attribute harus diisi.',
                'exists' => 'Data tidak ditemukan.',
            ]);

            if ($vaValidator->fails()) {
                return response()->json([
                    'status' => self::$status['BAD_REQUEST'],
                    'message' => $vaValidator->errors()->first(),
                    'datetime' => date('Y-m-d H:i:s')
                ], 422);
            }

            $deleted = DB::table('pasien_pendaftaran')->where('id', $request->id)->delete();

            if ($deleted === 0) {
                return response()->json([
                    'status' => self::$status['GAGAL'],
                    'message' => 'Gagal Hapus Data',
                    'datetime' => date('Y-m-d H:i:s')
                ], 400);
            }

            return response()->json([
                'status' => self::$status['SUKSES'],
                'message' => 'Berhasil Hapus Data',
                'datetime' => date('Y-m-d H:i:s')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'Terjadi Kesalahan Saat Proses Data: ' . $th->getMessage(),
                'datetime' => date('Y-m-d H:i:s')
            ], 400);
        }
    }
}
