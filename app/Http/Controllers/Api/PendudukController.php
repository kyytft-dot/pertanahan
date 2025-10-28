<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PendudukController extends Controller
{
    /**
     * Get all penduduk
     */
    public function index()
    {
        try {
            $penduduk = Penduduk::orderBy('NAMA', 'ASC')->get();
            return response()->json($penduduk);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create new penduduk
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NIK' => 'required|numeric|unique:penduduk,NIK',
            'NOMOR_KK' => 'required|numeric',
            'NAMA' => 'required|string|max:30',
            'ALAMAT' => 'required|string|max:100',
            'TGL_LAHIR' => 'required|date|before:2007-12-31',
            'NO_TELP' => 'required|string|max:30'
        ], [
            'NIK.required' => 'NIK wajib diisi',
            'NIK.unique' => 'NIK sudah terdaftar',
            'NAMA.required' => 'Nama wajib diisi',
            'TGL_LAHIR.before' => 'Tanggal lahir harus sebelum 2007-12-31',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 400);
        }

        try {
            $penduduk = Penduduk::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => $penduduk
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menambahkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update penduduk
     */
    public function update(Request $request, $nik)
    {
        $validator = Validator::make($request->all(), [
            'NOMOR_KK' => 'required|numeric',
            'NAMA' => 'required|string|max:30',
            'ALAMAT' => 'required|string|max:100',
            'TGL_LAHIR' => 'required|date|before:2007-12-31',
            'NO_TELP' => 'required|string|max:30'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 400);
        }

        try {
            $penduduk = Penduduk::findOrFail($nik);
            $penduduk->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $penduduk
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete penduduk
     */
    public function destroy($nik)
    {
        try {
            $penduduk = Penduduk::findOrFail($nik);
            $penduduk->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}