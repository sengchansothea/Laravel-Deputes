<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table1CompanyComposition extends Model
{
    use HasFactory;

    protected $table = 'tbl_1_company_composition';
    public $timestamps = false;
    protected $guarded = [];
}
