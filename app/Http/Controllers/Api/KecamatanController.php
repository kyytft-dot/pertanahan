<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kecamatan;

class KecamatanController extends Controller
{
    public function index()
    {
        return response()->json(Kecamatan::all());
    }

    public function show($id)
    {
        $item = Kecamatan::find($id);
        if (!$item) return response()->json(['message'=>'Data tidak ditemukan'], 404);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kota_kabupaten_id' => 'required|exists:kota_kabupatens,id'
        ]);
        return response()->json(Kecamatan::create($validated), 201);
    }

    public function update(Request $request, $id)
    {
        $item = Kecamatan::find($id);
        if (!$item) return response()->json(['message'=>'Data tidak ditemukan'], 404);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kota_kabupaten_id' => 'required|exists:kota_kabupatens,id'
        ]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = Kecamatan::find($id);
        if (!$item) return response()->json(['message'=>'Data tidak ditemukan'], 404);
        $item->delete();
        return response()->json(['message'=>'Data berhasil dihapus']);
    }
}
