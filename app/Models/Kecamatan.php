<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'm_kecamatan';
    protected $primaryKey = 'ID';
    public $timestamps = true;
    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';

    protected $fillable = [
        'ID_KOTA_KABUPATEN',
        'KODE_KECAMATAN',
        'NAMA_KECAMATAN'
    ];

    // Relasi ke Kota/Kabupaten
    public function kotaKabupaten()
    {
        return $this->belongsTo(KotaKabupaten::class, 'ID_KOTA_KABUPATEN', 'ID');
    }

    // Relasi ke Desa/Kelurahan
    public function desaKelurahan()
    {
        return $this->hasMany(DesaKelurahan::class, 'ID_KECAMATAN', 'ID');
    }
}