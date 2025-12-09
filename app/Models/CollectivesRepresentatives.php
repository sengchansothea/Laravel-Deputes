<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectivesRepresentatives extends Model
{
    use HasFactory;
    public $table = "tbl_collectives_representatives";
    public $timestamps=false;
    protected $guarded = [];
}
