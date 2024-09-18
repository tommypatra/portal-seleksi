<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikator extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pemeriksaSyarat()
    {
        return $this->hasMany(PemeriksaSyarat::class);
    }

    public function verifikatorSeleksi()
    {
        return $this->hasMany(VerifikatorSeleksi::class);
    }
}
