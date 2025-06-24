<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AntrianController extends Controller
{

    public function index()
    {
        $today = Carbon::today()->toDateString();

        $rows = DB::table('antrian_pasien as a')
            ->leftJoin('pasien_reservasi as r', 'r.id', '=', 'a.reservasi_id')
            ->leftJoin('pasien_pendaftaran as p', 'p.no_rm', '=', 'r.no_rm')
            ->select(
                'a.id',
                'a.no_antrian',
                'a.status',
                'a.ruangan',
                'a.waktu_masuk',
                'r.no_rm',
                'p.nama'
            )
            ->whereDate('a.waktu_masuk', $today)
            ->orderBy('a.ruangan')
            ->orderBy('a.no_antrian')
            ->get();

        return response()->json(['status' => 'success', 'data' => $rows]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'reservasi_id' => 'required|integer|exists:pasien_reservasi,id',
        ]);


        $reservasi = DB::table('pasien_reservasi')
            ->where('id', $request->reservasi_id)
            ->first();

        if (!$reservasi) {
            return response()->json(
                ['status' => 'error', 'message' => 'Reservasi tidak ditemukan'],
                404
            );
        }


        $ruangan = trim($reservasi->ruangan);

        /* Ambil huruf pertama dari dua kata pertama */
        $words = preg_split('/\s+/', $ruangan);     // pisah per spasi
        $prefix = '';
        foreach ($words as $w) {
            $prefix .= strtoupper(mb_substr(
                preg_replace('/[^A-Za-z]/', '', $w),  // buang non-huruf
                0,
                1                                  // ambil 1 huruf
            ));
            if (strlen($prefix) >= 2)
                break;          // cukup 2 huruf
        }

        /* Jika hanya 1 kata (mis. "Radiologi") */
        if (strlen($prefix) === 1) {
            $prefix .= strtoupper(mb_substr($words[0], 1, 1) ?: 'X'); // RA, atau RX
        }


        $today = Carbon::today()->toDateString();
        $last = DB::table('antrian_pasien')
            ->where('ruangan', $ruangan)
            ->whereDate('waktu_masuk', $today)
            ->orderByDesc('no_antrian')
            ->first();

        if (!$last || !preg_match('/\d+$/', $last->no_antrian, $m)) {
            $next = 1;
        } else {
            $next = intval($m[0]) + 1;
        }
        $no_antrian = $prefix . str_pad($next, 4, '0', STR_PAD_LEFT); // PU0001 …


        $row = Antrian::create([
            'reservasi_id' => $reservasi->id,
            'no_antrian' => $no_antrian,
            'ruangan' => $ruangan,
            'waktu_masuk' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Antrian ditambahkan',
            'data' => $row
        ], 201);
    }

    public function panggil($id)
    {
        DB::table('antrian')->where('id', $id)->update(['status' => 'dipanggil']);

        // jika ingin langsung redirect ke form pemeriksaan
        return response()->json([
            'message' => 'Pasien dipanggil',
            'id' => $id
        ]);
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,dipanggil,selesai'
        ]);

        $row = Antrian::findOrFail($id);
        $row->status = $request->status;
        $row->save();

        return response()->json(['status' => 'success', 'message' => 'Status diperbarui']);
    }
}
