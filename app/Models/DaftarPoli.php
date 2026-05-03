<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarPoli extends Model
{

    protected $table = 'daftar_poli';

    protected $fillable = [
        'id_jadwal',
        'id_pasien',
        'keluhan',
        'no_antrian',
        'status',
        'no_antrian_dipanggil',
        'payment_status',
    ];

    public function pasien()
    {
        // return $this->belongsTo(User::class, 'id_pasien');
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function jadwalPeriksa()
    {
        return $this->belongsTo(JadwalPeriksa::class, 'id_jadwal', 'id');
    }

    public function periksa()
    {
        return $this->hasOne(Periksa::class, 'id_daftar_poli');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_daftar_poli');
    }
}
