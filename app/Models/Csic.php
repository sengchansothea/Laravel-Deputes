<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Csic extends Model
{
    use HasFactory;
    public $table = "csic";
    public $timestamps=false;
    protected $guarded = [];
}
