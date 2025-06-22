<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $data = DB::table('antrian_pasien as a')
            ->leftJoin('pasien_reservasi as r', 'r.id', '=', 'a.reservasi_id')
            ->leftJoin('pasien_pendaftaran as p', 'p.no_rm', '=', 'r.no_rm')
            ->select(
                'a.id',
                'r.no_rm',
                'r.tgl_reservasi',
                'p.nama',
                'r.keluhan'
            )
            ->whereDate('a.waktu_masuk', $today)
            ->where('a.status', '=', 'dipanggil')
            ->orderBy('a.waktu_masuk')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = DB::table('antrian_pasien as a')
            ->leftJoin('pasien_reservasi as r', 'r.id', '=', 'a.reservasi_id')
            ->leftJoin('pasien_pendaftaran as p', 'p.no_rm', '=', 'r.no_rm')
            ->leftJoin('pemeriksaan_pasien as pr', 'pr.antrian_id', '=', 'a.id')
            // ⬇️ tambahkan baris ini
            ->leftJoin('master_diagnosa as md', 'md.id', '=', 'pr.diagnosa_id')
            ->select(
                'a.id as antrian_id',
                'r.no_rm',
                'p.nama',
                'r.tgl_reservasi',
                'r.keluhan',
                // ⬇️ ambil nama diagnosa dari master
                'md.nama  as diagnosa',
                'pr.tindakan',
                'pr.catatan'
            )
            ->where('a.id', $id)
            ->first();

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'diagnosa' => 'required|string',
            'tindakan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        DB::table('pemeriksaan_pasien')->updateOrInsert(
            ['antrian_id' => $id],
            [
                'diagnosa' => $request->diagnosa,
                'tindakan' => $request->tindakan,
                'catatan' => $request->catatan,
                'updated_at' => now(),
            ]
        );

        return response()->json(['message' => 'Pemeriksaan disimpan']);
    }


    public function selesai(Request $request, $id)
    {
        $antrian = DB::table('antrian_pasien')->where('id', $id)->first();
        if (!$antrian) {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }

        DB::table('antrian_pasien')->where('id', $id)->update([
            'status' => 'selesai',
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Status pemeriksaan diselesaikan']);
    }


}