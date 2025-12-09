<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeaIsic extends Model
{
    use HasFactory;
    public $table="tbl_nea_isic";
    public $timestamps=false;
    protected $guarded = [];
}
