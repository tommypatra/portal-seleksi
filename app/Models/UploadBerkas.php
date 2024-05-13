<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadBerkas extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function syarat()
    {
        return $this->belongsTo(Syarat::class);
    }
}
