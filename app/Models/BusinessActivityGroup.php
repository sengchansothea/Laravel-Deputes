<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessActivityGroup extends Model
{
    use HasFactory;
    public $table="tbl_business_activity_group";
    public $timestamps=false;
    protected $guarded = [];
}
