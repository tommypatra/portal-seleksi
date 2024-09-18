<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function topikInterview()
    {
        return $this->hasMany(TopikInterview::class);
    }
}
