<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficerRole extends Model
{
    use HasFactory;
    public $table = "tbl_officer_role";
    public $timestamps = false;
    protected $guarded = [];

    public function officers()
    {
        return $this->hasMany(Officer::class, "officer_role_id", 'id');
    }
}
