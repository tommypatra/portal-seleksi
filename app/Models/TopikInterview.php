<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopikInterview extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function nilaiInterview()
    {
        return $this->hasMany(NilaiInterview::class);
    }

    public function seleksi()
    {
        return $this->belongsTo(Seleksi::class);
    }

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }
}
