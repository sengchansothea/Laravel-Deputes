<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseSteps extends Model
{
    use HasFactory;
    public $table="tbl_case_steps";
    public $timestamps=false;
    protected $guarded = [];


}
