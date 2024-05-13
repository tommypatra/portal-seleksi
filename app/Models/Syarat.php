<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syarat extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function seleksi()
    {
        return $this->belongsTo(Seleksi::class);
    }

    public function uploadBerkas()
    {
        return $this->hasMany(UploadBerkas::class);
    }
}
