<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenebusanObatController extends Controller
{
    public function index()
    {
        $data = DB::table('resep_obat as ro')
            ->leftJoin('antrian_pasien as a', 'a.id', '=', 'ro.antrian_id')
            ->leftJoin('pasien_reservasi as r', 'r.id', '=', 'a.reservasi_id')
            ->leftJoin('pasien_pendaftaran as p', 'p.no_rm', '=', 'r.no_rm')
            ->leftJoin('penebusan_obat as po', 'po.resep_id', '=', 'ro.id')
            ->select(
                'ro.id as resep_id',
                'a.waktu_masuk as tanggal_periksa',
                'r.no_rm',
                'p.nama as nama_pasien',
                'r.keluhan',
                DB::raw("IFNULL(po.status_obat, 'Belum Lunas') as status_obat")
            )
            ->groupBy('ro.id')
            ->orderByDesc('a.waktu_masuk')
            ->get();

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function show($id)
    {
        $pasien = DB::table('resep_obat as ro')
            ->leftJoin('antrian_pasien as a', 'a.id', '=', 'ro.antrian_id')
            ->leftJoin('pasien_reservasi as r', 'r.id', '=', 'a.reservasi_id')
            ->leftJoin('pasien_pendaftaran as p', 'p.no_rm', '=', 'r.no_rm')
            ->leftJoin('pemeriksaan_pasien as pr', 'pr.antrian_id', '=', 'a.id')
            ->leftJoin('master_diagnosa as md', 'md.id', '=', 'pr.diagnosa_id')
            ->select(
                'ro.id as resep_id',
                'p.nama',
                'r.keluhan',
                'r.no_rm',
                'r.tgl_reservasi',
                'md.nama as diagnosa_nama',
                'pr.tindakan',
                'pr.tensi',
                'ro.antrian_id'
            )
            ->where('ro.id', $id)
            ->first();

        if (!$pasien) {
            return response()->json(['message' => 'Resep tidak ditemukan'], 404);
        }

        $resep = DB::table('resep_obat as ro')
            ->leftJoin('obat as o', 'o.id', '=', 'ro.obat_id')
            ->where('ro.antrian_id', $pasien->antrian_id)
            ->select('ro.*', 'o.nama_obat', 'o.harga_jual')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => array_merge((array) $pasien, ['resep' => $resep])
        ]);
    }

    public function updateStatus($id)
    {
        DB::table('penebusan_obat')->updateOrInsert(
            ['resep_id' => $id],
            ['status_obat' => 'Lunas', 'updated_at' => now()]
        );

        return response()->json(['message' => 'Status obat diperbarui ke Lunas']);
    }
}
