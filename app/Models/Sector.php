<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;
    public $table = "tbl_sector";
    public $timestamps = false;
    protected $guarded = [];
}
