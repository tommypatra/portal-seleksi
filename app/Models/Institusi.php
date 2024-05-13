<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institusi extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function subInstitusi()
    {
        return $this->hasMany(SubInstitusi::class);
    }
}
