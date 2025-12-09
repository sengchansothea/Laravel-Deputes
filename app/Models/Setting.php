<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    public $table="tbl_setting";
    public $timestamps=false;
    protected $guarded = [];
}
