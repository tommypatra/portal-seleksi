<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiInterview extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function wawancara()
    {
        return $this->belongsTo(Wawancara::class);
    }

    public function topikInterview()
    {
        return $this->belongsTo(topikInterview::class);
    }
}
