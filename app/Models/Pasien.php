<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model 
{
    use HasFactory;

    
    protected $table = 'pasien'; 

    protected $fillable = ['nama', 'alamat', 'no_ktp', 'no_hp', 'no_rm'];

        public function user()
    {
        // Ini kebalikan dari hasOne
        return $this->belongsTo(User::class, 'id_user');
    }
}