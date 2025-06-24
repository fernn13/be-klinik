<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepObatController extends Controller
{
    // Tampilkan semua resep (opsional)
    public function index()
    {
        $data = DB::table('resep_obat as r')
            ->leftJoin('antrian_pasien as a', 'a.id', '=', 'r.antrian_id')
            ->leftJoin('pasien_reservasi as pr', 'pr.id', '=', 'a.reservasi_id')
            ->leftJoin('pasien_pendaftaran as p', 'p.no_rm', '=', 'pr.no_rm')
            ->leftJoin('obat as o', 'o.id', '=', 'r.obat_id')
            ->select(
                'r.id',
                'p.nama as nama_pasien',
                'pr.no_rm',
                'o.nama_obat',
                'r.dosis',
                'r.frekuensi',
                'r.created_at'
            )
            ->orderBy('r.created_at', 'desc')
            ->get();

        return response()->json(['data' => $data]);
    }

    // Detail resep berdasarkan antrian_id
    public function show($id)
    {
        // Ambil data pasien dan pemeriksaan
        $pasien = DB::table('resep_obat as ro')
            ->leftJoin('antrian_pasien as a', 'a.id', '=', 'ro.antrian_id')
            ->leftJoin('pasien_reservasi as r', 'r.id', '=', 'a.reservasi_id')
            ->leftJoin('pasien_pendaftaran as p', 'p.no_rm', '=', 'r.no_rm')
            ->leftJoin('pemeriksaan_pasien as pr', 'pr.antrian_id', '=', 'a.id')
            ->leftJoin('master_diagnosa as d', 'd.id', '=', 'pr.diagnosa_id')
            ->select(
                'p.nama',
                'r.keluhan',
                'r.no_rm',
                'r.tgl_reservasi',
                'pr.tensi',
                'pr.tindakan',
                'd.nama as diagnosa_nama'
            )
            ->where('ro.id', $id)
            ->first();

        // Ambil detail resep obat
        $resep = DB::table('resep_obat as ro')
            ->leftJoin('obat as o', 'o.id', '=', 'ro.obat_id')
            ->select(
                'ro.dosis',
                'ro.frekuensi as aturan',
                'ro.jumlah',
                'o.nama_obat',
                'o.harga_jual'
            )
            ->where('ro.id', $id)
            ->get();

        if (!$pasien) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => array_merge((array) $pasien, ['resep' => $resep])
        ]);
    }



    // Simpan resep baru dan update status antrian menjadi selesai
    public function store(Request $request, $id)
    {
        foreach ($request->resep as $item) {
            DB::table('resep_obat')->insert([
                'antrian_id' => $id, // ← perbaikan utama di sini
                'obat_id' => $item['obat_id'],
                'dosis' => $item['dosis'],
                'frekuensi' => $item['frekuensi'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Resep berhasil disimpan dan status selesai']);
    }



    // (Opsional) Hapus semua resep untuk 1 antrian (jika dibutuhkan)
    public function destroy($antrian_id)
    {
        DB::table('resep_obat')->where('antrian_id', $antrian_id)->delete();
        return response()->json(['message' => 'Resep berhasil dihapus']);
    }
}
