<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ReservasiController extends Controller
{
    public function data(Request $request)
    {
        //dd($request->all());
        try {
            $vaData = DB::table('pasien_reservasi')
                ->select('id', 'no_rm', 'nama', 'no_ktp', 'tgl_reservasi', 'kode_kunjungan', 'ruangan', 'keluhan')
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($item) {
                    $item->tgl_reservasi = Carbon::parse($item->tgl_reservasi)
                        ->timezone('Asia/Jakarta')
                        ->format('d/m/Y H:i:s');
                    return $item;
                });

            return response()->json([
                'status' => self::$status['SUKSES'],
                'message' => 'SUKSES',
                'data' => $vaData,
                'datetime' => Carbon::now()->format('d/m/Y H:i:s')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'Terjadi Kesalahan Saat Proses Data',
                'datetime' => Carbon::now()->format('d/m/Y H:i:s')
            ], 400);
        }
    }

    private function generateNomorRM()
    {
        $lastKK = Reservasi::orderBy('kode_kunjungan', 'desc')->first();

        if (!$lastKK) {
            return 'KK0001';
        }

        // Ambil angka dari kode terakhir
        $number = intval(substr($lastKK->kode_kunjungan, 2));
        $nextNumber = $number + 1;

        // Format ulang dengan leading zero
        return 'KK' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {

        try {

            // Generate no_rm otomatis
            $kode_kunjungan = $this->generateNomorRM();

            // Tambahkan no_rm ke request data
            $data = $request->all();
            $data['kode_kunjungan'] = $kode_kunjungan;

            $data['tgl_reservasi'] = Carbon::parse($request->tgl_reservasi)->format('Y-m-d H:i:s');
            
            // Validasi data (tanpa validasi unique karena no_rm sudah dijamin unik)
            $vaValidator = Validator::make($data, [
                'no_rm' => 'required|string',
                'nama' => 'required|string|max:100',
                'no_ktp' => 'nullable|string|max:30',
                'tgl_reservasi' => 'required|date',
                'kode_kunjungan' => 'required|unique:pasien_reservasi,kode_kunjungan',
                'ruangan' => 'nullable|string|max:50',
                'keluhan' => 'required|string',
            ]);

            if ($vaValidator->fails()) {
                return response()->json([
                    'status' => self::$status['BAD_REQUEST'],
                    'message' => $vaValidator->errors()->first(),
                    'datetime' => now()
                ], 422);
            }

            Reservasi::create($data);

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



    public function update(Request $request)
    {
        try {
            $vaValidator = Validator::make($request->all(), [
                'id' => 'required|exists:pasien_pendaftaran,id',
                'nama' => 'required|max:100',
                'no_rm' => 'required|max:50',
                'tempat_lahir' => 'nullable|string|max:100',
                'tgl_lahir' => 'nullable|date',
                'jns_kelamin' => 'nullable|in:L,P',
                'alamat' => 'nullable|string',
                'no_tlp' => 'nullable|string|max:20',
                'pendidikan' => 'nullable|string|max:50',
                'pekerjaan' => 'nullable|string|max:50',
                'no_ktp' => 'nullable|string|max:30',
                'no_asuransi' => 'nullable|string|max:30',
                'jns_asuransi' => 'nullable|string|max:50',
            ], [
                'required' => 'Kolom :attribute harus diisi.',
                'exists' => 'Data tidak ditemukan.',
                'max' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
            ]);

            if ($vaValidator->fails()) {
                return response()->json([
                    'status' => self::$status['BAD_REQUEST'],
                    'message' => $vaValidator->errors()->first(),
                    'datetime' => Carbon::now()->format('d/m/Y H:i:s')
                ], 422);
            }

            $update = DB::table('pasien_pendaftaran')
                ->where('id', $request->id)
                ->update($request->except(['id']));

            if ($update === 0) {
                return response()->json([
                    'status' => self::$status['GAGAL'],
                    'message' => 'Gagal Update Data',
                    'datetime' => Carbon::now()->format('d/m/Y H:i:s')
                ], 400);
            }

            return response()->json([
                'status' => self::$status['SUKSES'],
                'message' => 'Berhasil Update Data',
                'datetime' => Carbon::now()->format('d/m/Y H:i:s')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'Terjadi Kesalahan Saat Proses Data: ' . $th->getMessage(),
                'datetime' => Carbon::now()->format('d/m/Y H:i:s')
            ], 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            $vaValidator = Validator::make($request->all(), [
                'id' => 'required|exists:pasien_reservasi,id',
            ], [
                'required' => 'Kolom :attribute harus diisi.',
                'exists' => 'Data tidak ditemukan.',
            ]);

            if ($vaValidator->fails()) {
                return response()->json([
                    'status' => self::$status['BAD_REQUEST'],
                    'message' => $vaValidator->errors()->first(),
                    'datetime' => Carbon::now()->format('d/m/Y H:i:s')
                ], 422);
            }

            $deleted = DB::table('pasien_reservasi')->where('id', $request->id)->delete();

            if ($deleted === 0) {
                return response()->json([
                    'status' => self::$status['GAGAL'],
                    'message' => 'Gagal Hapus Data',
                    'datetime' => Carbon::now()->format('d/m/Y H:i:s')
                ], 400);
            }

            return response()->json([
                'status' => self::$status['SUKSES'],
                'message' => 'Berhasil Hapus Data',
                'datetime' => Carbon::now()->format('d/m/Y H:i:s')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'Terjadi Kesalahan Saat Proses Data: ' . $th->getMessage(),
                'datetime' => Carbon::now()->format('d/m/Y H:i:s')
            ], 400);
        }
    }
}