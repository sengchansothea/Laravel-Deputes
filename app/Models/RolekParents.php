<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolekParents extends Model
{
    use HasFactory;
    public $table="role_k_category";
    public $timestamps=false;
    protected $guarded = [];
}
