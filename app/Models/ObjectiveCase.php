<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectiveCase extends Model
{
    use HasFactory;
    public $table="tbl_objective_case";
    public $timestamps=false;
    protected $guarded = [];
}
