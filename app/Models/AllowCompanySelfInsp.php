<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowCompanySelfInsp extends Model
{
    use HasFactory;
    public $table="tbl_allow_company_self_insp";
    public $timestamps=false;
    protected $guarded = [];
}
