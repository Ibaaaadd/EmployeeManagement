<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory; 

    protected $fillable = [
        'id','name', 'alamat', 'nomor', 'jabatan', 'gaji',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function riwayat_gajis()
    {
        return $this->hasMany(RiwayatGaji::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

}
