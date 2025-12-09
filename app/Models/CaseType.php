<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseType extends Model
{
    use HasFactory;
    public $table="tbl_case_type";
    public $timestamps=false;
    protected $guarded = [];
}
