<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeleksiAsal extends Model
{
    protected $guarded = ["id"];

    public function seleksi()
    {
        return $this->belongsTo(Seleksi::class);
    }

    public function subInstitusi()
    {
        return $this->belongsTo(SubInstitusi::class);
    }
}
