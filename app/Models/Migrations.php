<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Migrations extends Model
{
    use HasFactory;
    public $table="migrations";
    public $timestamps=false;
    protected $guarded = [];
}
