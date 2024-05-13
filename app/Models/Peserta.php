<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function subInstitusi()
    {
        return $this->belongsTo(SubInstitusi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }
}
