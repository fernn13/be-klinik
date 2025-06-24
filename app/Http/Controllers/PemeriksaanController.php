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
            ->leftJoin('master_diagnosa as md', 'md.id', '=', 'pr.diagnosa_id')
            ->leftJoin('resep_obat as ro', 'ro.antrian_id', '=', 'a.id') // ← tambahkan ini
            ->leftJoin('obat as o', 'o.id', '=', 'ro.obat_id') // ← agar bisa ambil nama obat
            ->select(
                'a.id as antrian_id',
                'r.no_rm',
                'p.nama',
                'r.tgl_reservasi',
                'r.keluhan',
                'md.nama as diagnosa_nama',
                'pr.tindakan',
                'pr.tensi',
                // ↓ tambahkan data resep
                DB::raw("GROUP_CONCAT(o.nama_obat, ' - ', ro.dosis, ' - ', ro.frekuensi SEPARATOR '\n') as resep")
            )
            ->where('a.id', $id)
            ->groupBy(
                'a.id',
                'r.no_rm',
                'p.nama',
                'r.tgl_reservasi',
                'r.keluhan',
                'md.nama',
                'pr.tindakan',
                'pr.tensi'
            )
            ->first();

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $resep = DB::table('resep_obat as ro')
        ->leftJoin('obat as o', 'o.id', '=', 'ro.obat_id')
        ->select('o.nama_obat', 'ro.dosis', 'ro.frekuensi as aturan')
        ->where('ro.antrian_id', $id)
        ->get();
        $data->resep = $resep;

        return response()->json(['status' => 'success', 'data' => $data]);
    }


    public function store(Request $request, $id)
    {
        $request->validate([
            'diagnosa' => 'required|integer', // ID dari master_diagnosa
            'tensi' => 'nullable|string',
            'tindakan' => 'nullable|string',
        ]);

        DB::table('pemeriksaan_pasien')->updateOrInsert(
            ['antrian_id' => $id],
            [
                'diagnosa_id' => $request->diagnosa, // ← ubah ke diagnosa_id
                'tensi' => $request->tensi,
                'tindakan' => $request->tindakan,
                'updated_at' => now()
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