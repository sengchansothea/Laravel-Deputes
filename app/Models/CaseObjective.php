<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseObjective extends Model
{
    use HasFactory;
    public $table="tbl_objective_case";
    public $timestamps=false;
    protected $guarded = [];


}
