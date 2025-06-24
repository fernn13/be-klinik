<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Antrian;
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
            $vaData = DB::table('pasien_reservasi as r')
                ->leftJoin('pasien_pendaftaran as p', 'p.no_rm', '=', 'r.no_rm')
                ->select([
                    'r.id',
                    'r.no_rm',
                    'p.no_ktp',
                    'p.nama',
                    'r.tgl_reservasi',
                    'r.kode_kunjungan',
                    'r.ruangan',
                    'r.keluhan',
                ])
                ->orderByDesc('r.created_at')
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
            $kode_kunjungan = $this->generateNomorRM();

            $vaValidator = Validator::make($request->all(), [
                'no_rm' => 'required|string|exists:pasien_pendaftaran,no_rm',
                'tgl_reservasi' => 'required|date',
                'ruangan' => 'required|string|max:50',
                'keluhan' => 'required|string',
            ]);

            if ($vaValidator->fails()) {
                return response()->json([
                    'status' => self::$status['BAD_REQUEST'],
                    'message' => $vaValidator->errors()->first(),
                    'datetime' => now()
                ], 422);
            }

            $reservasi = Reservasi::create([
                'no_rm' => $request->no_rm,
                'tgl_reservasi' => Carbon::parse($request->tgl_reservasi),
                'kode_kunjungan' => $kode_kunjungan,
                'ruangan' => $request->ruangan,
                'keluhan' => $request->keluhan,
            ]);

            // ============ INSERT OTOMATIS KE ANTRIAN ===============
            $ruangan = trim($reservasi->ruangan);
            $words = preg_split('/\s+/', $ruangan);
            $prefix = '';
            foreach ($words as $w) {
                $prefix .= strtoupper(mb_substr(preg_replace('/[^A-Za-z]/', '', $w), 0, 1));
                if (strlen($prefix) >= 2)
                    break;
            }
            if (strlen($prefix) === 1) {
                $prefix .= strtoupper(mb_substr($words[0], 1, 1) ?: 'X');
            }

            $today = Carbon::today()->toDateString();
            $last = DB::table('antrian_pasien')
                ->where('ruangan', $ruangan)
                ->whereDate('waktu_masuk', $today)
                ->orderByDesc('no_antrian')
                ->first();

            $next = (!$last || !preg_match('/\d+$/', $last->no_antrian, $m))
                ? 1
                : intval($m[0]) + 1;

            $no_antrian = $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);

            Antrian::create([
                'reservasi_id' => $reservasi->id,
                'no_antrian' => $no_antrian,
                'ruangan' => $ruangan,
                'waktu_masuk' => now(),
                'status' => 'menunggu',
            ]);

            return response()->json([
                'status' => self::$status['SUKSES'],
                'message' => 'Data reservasi & antrian tersimpan',
                'datetime' => now()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'Gagal simpan: ' . $th->getMessage(),
                'datetime' => now()
            ], 400);
        }
    }

    public function show(Request $request)
    {
        $id = $request->query('id');
        $data = Reservasi::with('pendaftaran')->find($id);

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['data' => $data], 200);
    }



    public function update(Request $request)
    {
        try {
            $vaValidator = Validator::make($request->all(), [
                'id' => 'required|exists:pasien_reservasi,id',
                'no_rm' => 'required|max:50',
                'tgl_reservasi' => 'required|date',
                'ruangan' => 'required|string|max:50',
                'keluhan' => 'required|string',
            ], [
                'required' => 'Kolom :attribute harus diisi.',
                'exists' => 'Data tidak ditemukan.',
                'max' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
            ]);

            if ($vaValidator->fails()) {
                return response()->json([
                    'status' => self::$status['BAD_REQUEST'],
                    'message' => $vaValidator->errors()->first(),
                    'datetime' => now()
                ], 422);
            }

            $update = DB::table('pasien_reservasi')
                ->where('id', $request->id)
                ->update([
                    'no_rm' => $request->no_rm,
                    'tgl_reservasi' => Carbon::parse($request->tgl_reservasi),
                    'ruangan' => $request->ruangan,
                    'keluhan' => $request->keluhan,
                ]);

            if ($update === 0) {
                return response()->json([
                    'status' => self::$status['GAGAL'],
                    'message' => 'Gagal Update Data',
                    'datetime' => now()
                ], 400);
            }

            return response()->json([
                'status' => self::$status['SUKSES'],
                'message' => 'Berhasil Update Data',
                'datetime' => now()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'Terjadi Kesalahan Saat Update: ' . $th->getMessage(),
                'datetime' => now()
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