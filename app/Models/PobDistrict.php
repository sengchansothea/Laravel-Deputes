<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PobDistrict extends Model
{
    use HasFactory;
    public $table="camdx_district";
    public $timestamps=false;
    protected $guarded = [];
}
