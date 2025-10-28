<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'm_provinsi';
    protected $primaryKey = 'ID';
    public $timestamps = true;
    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';

    protected $fillable = [
        'KODE_PROVINSI',
        'NAMA_PROVINSI'
    ];

    // Relasi ke Kota/Kabupaten
    public function kotaKabupaten()
    {
        return $this->hasMany(KotaKabupaten::class, 'ID_PROVINSI', 'ID');
    }
}