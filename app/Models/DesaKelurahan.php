<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class desaKelurahan extends Model
{
    use HasFactory;

    protected $table = 'm_desa_kelurahan';
    protected $primaryKey = 'ID';
    public $timestamps = true;
    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';

    protected $fillable = [
        'ID_KECAMATAN',
        'KODE_DESA_KELURAHAN',
        'NAMA_DESA_KELURAHAN',
        'JENIS_WILAYAH',
        'KODE_POS'
    ];

    // Relasi ke Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(kecamatan::class, 'ID_KECAMATAN', 'ID');
    }
}