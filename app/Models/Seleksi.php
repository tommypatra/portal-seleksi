<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seleksi extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    protected static function boot()
    {
        parent::boot();
        //function dipakai, atur logika atau mendefinisikan nilai sebelum simpan data
        static::creating(function ($dt) {
            $user_id = auth()->check() ? auth()->id() : 1;
            $dt->user_id = $user_id;
        });

        static::updating(function ($dt) {
            $user_id = auth()->check() ? auth()->id() : 1;
            $dt->user_id = $user_id;
        });
    }


    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }

    public function syarat()
    {
        return $this->hasMany(Syarat::class);
    }

    public function roleSeleksi()
    {
        return $this->hasMany(RoleSeleksi::class);
    }

    public function topikInterview()
    {
        return $this->hasMany(TopikInterview::class);
    }

    public function seleksiAsal()
    {
        return $this->hasMany(SeleksiAsal::class);
    }

    public function verifikatorSeleksi()
    {
        return $this->hasMany(VerifikatorSeleksi::class);
    }
}
