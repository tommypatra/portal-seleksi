<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function pemeriksaSyarat()
    {
        return $this->hasMany(PemeriksaSyarat::class);
    }

    public function uploadBerkas()
    {
        return $this->hasMany(UploadBerkas::class);
    }

    public function seleksi()
    {
        return $this->belongsTo(Seleksi::class);
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function wawancara()
    {
        return $this->hasMany(Wawancara::class);
    }

    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class);
    }
}
