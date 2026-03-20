<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    protected $table = 'penggajians';

    protected $fillable = [
        'pegawai_id',
        'periode',
        'insentif',
        'potongan',
        'total_gaji',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
