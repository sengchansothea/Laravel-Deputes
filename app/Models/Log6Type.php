<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log6Type extends Model
{
    use HasFactory;
    public $table="tbl_log6_type";
    public $timestamps=false;
    protected $guarded = [];
}
