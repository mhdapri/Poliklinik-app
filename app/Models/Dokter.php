<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model 
{
    use HasFactory;

    // Pastikan nama tabelnya sesuai dengan di database Anda
    protected $table = 'dokter'; 

    // Daftarkan kolom yang boleh diisi (mass assignment)
    protected $fillable = ['nama', 'alamat', 'no_hp', 'id_poli'];

    // Relasi balik: Banyak Dokter dimiliki oleh satu Poli
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function user()
    {
        // Ini kebalikan dari hasOne
        return $this->belongsTo(User::class, 'id_user');
    }
}