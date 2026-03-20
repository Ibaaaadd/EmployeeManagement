<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatGaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'periode',
        'tanggal',
        'gaji_pokok',
        'insentif',
        'potongan',
        'total_gaji',
        'pdf_path',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
