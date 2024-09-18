<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleSeleksi extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(Verifikator::class);
    }

    public function seleksi()
    {
        return $this->belongsTo(Seleksi::class);
    }

    public function pemeriksaSyarat()
    {
        return $this->hasMany(PemeriksaSyarat::class);
    }

    public function wawancara()
    {
        return $this->hasMany(Wawancara::class);
    }
}
