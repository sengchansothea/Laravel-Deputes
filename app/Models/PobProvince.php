<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PobProvince extends Model
{
    use HasFactory;
    public $table="camdx_province";
    public $timestamps=false;
    protected $guarded = [];
}
