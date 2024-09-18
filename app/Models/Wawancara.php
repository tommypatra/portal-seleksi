<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wawancara extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function nilaiInterview()
    {
        return $this->hasMany(NilaiInterview::class);
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    //dihapus
    public function interview()
    {
        return $this->belongsTo(Interviewer::class);
    }

    public function roleSeleksi()
    {
        return $this->belongsTo(RoleSeleksi::class);
    }
}
