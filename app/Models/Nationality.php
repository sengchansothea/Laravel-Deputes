<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    use HasFactory;
    public $table="tbl_nationality";
    public $timestamps=false;
    protected $guarded = [];
}
