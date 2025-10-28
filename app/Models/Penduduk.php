<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    protected $table = 'penduduk';
    protected $primaryKey = 'NIK';
    public $incrementing = false;
    protected $keyType = 'integer';
    
    protected $fillable = [
        'NIK',
        'NOMOR_KK',
        'NAMA',
        'ALAMAT',
        'TGL_LAHIR',
        'NO_TELP'
    ];

    protected $casts = [
        'TGL_LAHIR' => 'date',
        'NIK' => 'integer',
        'NOMOR_KK' => 'integer',
    ];
}