<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log6Status extends Model
{
    use HasFactory;
    public $table="tbl_log6_status";
    public $timestamps=false;
    protected $guarded = [];
}
