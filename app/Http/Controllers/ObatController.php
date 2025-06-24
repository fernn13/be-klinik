<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ObatController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Obat::all()]);
    }


    private function generateKodeObat()
    {
        $lastObat = \App\Models\Obat::orderBy('id', 'desc')->first();
        $lastId = $lastObat ? $lastObat->id : 0;
        return 'OBT' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_obat' => 'required',
            'satuan' => 'required',
            'isi' => 'required|integer',
            'stok' => 'required|integer',
            'harga_jual' => 'required|numeric',
            'minimum_persediaan' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $request['kode_obat'] = $this->generateKodeObat(); // ← otomatis generate kode
        $obat = Obat::create($request->all());

        return response()->json(['message' => 'Data obat berhasil ditambahkan', 'data' => $obat]);
    }


    public function update(Request $request)
    {
        $obat = Obat::find($request->id);

        if (!$obat) {
            return response()->json(['message' => 'Obat tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'kode_obat' => 'required|unique:obat,kode_obat,' . $obat->id,
            'nama_obat' => 'required',
            'satuan' => 'required',
            'isi' => 'required|integer',
            'stok' => 'required|integer',
            'harga_jual' => 'required|numeric',
            'minimum_persediaan' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $obat->update($request->all());

        return response()->json(['message' => 'Data obat berhasil diperbarui', 'data' => $obat]);
    }

    public function destroy($id)
    {
        $obat = Obat::find($id);
        if (!$obat) {
            return response()->json(['message' => 'Obat tidak ditemukan'], 404);
        }

        $obat->delete();

        return response()->json(['message' => 'Data obat berhasil dihapus']);
    }

    public function import(Request $request)
    {
        $data = $request->input('data');

        if (!$data || !is_array($data)) {
            return response()->json(['message' => 'Format data tidak valid'], 400);
        }

        foreach ($data as $item) {
            Obat::updateOrCreate(
                ['kode_obat' => $item['kode_obat']],
                [
                    'nama_obat' => $item['nama_obat'] ?? '',
                    'satuan' => $item['satuan'] ?? '',
                    'isi' => $item['isi'] ?? 0,
                    'stok' => $item['stok'] ?? 0,
                    'harga_jual' => $item['harga_jual'] ?? 0,
                    'minimum_persediaan' => $item['minimum_persediaan'] ?? 0,
                ]
            );
        }

        return response()->json(['message' => 'Impor data obat berhasil']);
    }
}
