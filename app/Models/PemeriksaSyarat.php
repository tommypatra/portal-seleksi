<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaSyarat extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function roleSeleksi()
    {
        return $this->belongsTo(RoleSeleksi::class);
    }

    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class);
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(Verifikator::class);
    }
}
