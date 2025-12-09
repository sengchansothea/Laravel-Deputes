<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectivesCause extends Model
{
    use HasFactory;
    public $table="tbl_collectives_cause";
    public $timestamps=false;
    protected $guarded = [];
}
