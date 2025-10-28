<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KotaKabupaten;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KotaKabupatenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $kotaKabupaten = KotaKabupaten::with('provinsi')->get();
            
            return response()->json([
                'status' => true,
                'message' => 'Data kota/kabupaten berhasil diambil',
                'data' => $kotaKabupaten
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data kota/kabupaten',
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
                'id_provinsi' => 'required|exists:provinsi,id',
                'kode_kota_kabupaten' => 'required|string|max:10|unique:kota_kabupaten,kode_kota_kabupaten',
                'nama_kota_kabupaten' => 'required|string|max:255'
            ]);

            $kotaKabupaten = KotaKabupaten::create([
                'id_provinsi' => $request->id_provinsi,
                'kode_kota_kabupaten' => $request->kode_kota_kabupaten,
                'nama_kota_kabupaten' => $request->nama_kota_kabupaten
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data kota/kabupaten berhasil ditambahkan',
                'data' => $kotaKabupaten->load('provinsi')
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
                'message' => 'Gagal menambahkan data kota/kabupaten',
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
            $kotaKabupaten = KotaKabupaten::with('provinsi')->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Data kota/kabupaten berhasil diambil',
                'data' => $kotaKabupaten
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data kota/kabupaten tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data kota/kabupaten',
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
            $kotaKabupaten = KotaKabupaten::findOrFail($id);

            $request->validate([
                'id_provinsi' => 'required|exists:provinsi,id',
                'kode_kota_kabupaten' => 'required|string|max:10|unique:kota_kabupaten,kode_kota_kabupaten,' . $id,
                'nama_kota_kabupaten' => 'required|string|max:255'
            ]);

            $kotaKabupaten->update([
                'id_provinsi' => $request->id_provinsi,
                'kode_kota_kabupaten' => $request->kode_kota_kabupaten,
                'nama_kota_kabupaten' => $request->nama_kota_kabupaten
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data kota/kabupaten berhasil diupdate',
                'data' => $kotaKabupaten->load('provinsi')
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data kota/kabupaten tidak ditemukan'
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
                'message' => 'Gagal mengupdate data kota/kabupaten',
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
            $kotaKabupaten = KotaKabupaten::findOrFail($id);
            $kotaKabupaten->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data kota/kabupaten berhasil dihapus'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data kota/kabupaten tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data kota/kabupaten',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get kota/kabupaten by provinsi ID.
     */
    public function getByProvinsi(string $idProvinsi): JsonResponse
    {
        try {
            $kotaKabupaten = KotaKabupaten::with('provinsi')
                ->where('id_provinsi', $idProvinsi)
                ->get();

            if ($kotaKabupaten->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Tidak ada data kota/kabupaten untuk provinsi ini',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data kota/kabupaten berhasil diambil',
                'data' => $kotaKabupaten
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data kota/kabupaten',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}