<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProvinsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $provinsi = Provinsi::all();
            
            return response()->json([
                'status' => true,
                'message' => 'Data provinsi berhasil diambil',
                'data' => $provinsi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data provinsi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'kode_provinsi' => 'required|string|max:10|unique:provinsi,kode_provinsi',
                'nama_provinsi' => 'required|string|max:255'
            ]);

            $provinsi = Provinsi::create([
                'kode_provinsi' => $request->kode_provinsi,
                'nama_provinsi' => $request->nama_provinsi
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data provinsi berhasil ditambahkan',
                'data' => $provinsi
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan data provinsi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $provinsi = Provinsi::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Data provinsi berhasil diambil',
                'data' => $provinsi
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data provinsi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data provinsi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $provinsi = Provinsi::findOrFail($id);

            $request->validate([
                'kode_provinsi' => 'required|string|max:10|unique:provinsi,kode_provinsi,' . $id,
                'nama_provinsi' => 'required|string|max:255'
            ]);

            $provinsi->update([
                'kode_provinsi' => $request->kode_provinsi,
                'nama_provinsi' => $request->nama_provinsi
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data provinsi berhasil diupdate',
                'data' => $provinsi
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data provinsi tidak ditemukan'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate data provinsi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $provinsi = Provinsi::findOrFail($id);
            $provinsi->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data provinsi berhasil dihapus'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data provinsi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data provinsi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}