<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getSummary()
    {
        $today = now()->toDateString();

        $jumlahPasienHariIni = DB::table('pasien_reservasi')
            ->whereDate('tgl_reservasi', $today)
            ->count();

        $totalPasien = DB::table('pasien_pendaftaran')->count();

        $pasienBaru = DB::table('pasien_reservasi as pr')
            ->join('pasien_pendaftaran as pp', function ($join) {
                $join->on('pp.no_rm', '=', 'pr.no_rm')
                    ->whereDate('pp.created_at', '=', DB::raw('DATE(pr.tgl_reservasi)'));
            })
            ->whereDate('pr.tgl_reservasi', $today)
            ->count();

        $pasienLama = DB::table('pasien_reservasi as pr')
            ->whereDate('pr.tgl_reservasi', $today)
            ->whereNotIn('pr.no_rm', function ($query) use ($today) {
                $query->select('no_rm')
                    ->from('pasien_pendaftaran')
                    ->whereDate('created_at', $today);
            })
            ->count();


        $stokObat = DB::table('obat')
            ->select('kode_obat', 'nama_obat', 'stok')
            ->get();

        $kunjunganChart = DB::table('pasien_reservasi')
            ->select(
                DB::raw('DATE(tgl_reservasi) as tanggal'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(DB::raw('DATE(tgl_reservasi)'))
            ->orderBy('tanggal', 'desc')
            ->limit(7)
            ->get();

        return response()->json([
            'pasien_hari_ini' => $jumlahPasienHariIni,
            'total_pasien' => $totalPasien,
            'pasien_baru' => $pasienBaru,
            'pasien_lama' => $pasienLama,
            'stok_obat' => $stokObat,
            'kunjungan_chart' => $kunjunganChart->reverse()->values(), // agar urut dari paling lama ke terbaru
        ]);
    }

}
