<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonInUnit extends Model
{
    use HasFactory;
    public $table = "tbl_responsible_person";
    public $timestamps = false;
    protected $guarded = [];



}
