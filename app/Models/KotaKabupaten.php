<?php

// ============================================
// FILE: app/Models/KotaKabupaten.php
// ============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaKabupaten extends Model
{
    use HasFactory;

    protected $table = 'm_kota_kabupaten';
    protected $primaryKey = 'ID';
    public $timestamps = true;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'ID_PROVINSI',
        'KODE_KOTA',
        'NAMA_KOTA'
    ];

    protected $casts = [
        'ID' => 'integer',
        'ID_PROVINSI' => 'integer',
    ];

    // Relasi ke tabel provinsi
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'ID_PROVINSI', 'ID');
    }
}