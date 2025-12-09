<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PobCommune extends Model
{
    use HasFactory;
    public $table="camdx_commune";
    public $timestamps=false;
    protected $guarded = [];
}
