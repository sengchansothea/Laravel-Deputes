<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessActivity extends Model
{
    use HasFactory;
    public $table="tbl_business_activity";
    public $timestamps=false;
    protected $guarded = [];

    public function group()
    {
        return $this->hasOne(BusinessActivityGroup::class, "id", 'group_id');
    }
}
