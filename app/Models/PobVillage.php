<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PobVillage extends Model
{
    use HasFactory;
    public $table="camdx_village";
    public $timestamps=false;
    protected $guarded = [];
}
