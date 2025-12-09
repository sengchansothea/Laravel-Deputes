<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseAttendantType extends Model
{
    use HasFactory;
    public $table="tbl_case_attendant_type";
    public $timestamps=false;
    protected $guarded = [];
}
