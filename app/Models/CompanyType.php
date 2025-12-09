<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyType extends Model
{
    use HasFactory;
    public $table="tbl_company_type";
    public $timestamps=false;
    protected $guarded = [];
}
