<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NextTimeStatus extends Model
{
    use HasFactory;
    public $table="tbl_next_time_status";
    public $timestamps=false;
    protected $guarded = [];
}
