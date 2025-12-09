<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSector extends Model
{
    use HasFactory;
    public $table = "tbl_sub_sector";
    public $timestamps = false;
    protected $guarded = [];
}
