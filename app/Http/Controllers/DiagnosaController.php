<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosaController extends Controller
{
    public function index()
    {
        $data = DB::table('master_diagnosa')
            ->orderBy('kode')
            ->get();

        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'nullable|integer',
            'kode' => 'required|string|max:20',
            'nama' => 'required|string',
        ]);

        DB::table('master_diagnosa')->updateOrInsert(
            ['id' => $request->id],
            [
                'kode' => $request->kode,
                'nama' => $request->nama,
                'updated_at' => now()
            ]
        );

        return response()->json(['message' => 'Diagnosa berhasil disimpan']);
    }
    public function destroy($id)
    {
        DB::table('master_diagnosa')->where('id', $id)->delete();
        return response()->json(['message' => 'Diagnosa berhasil dihapus']);
    }

}