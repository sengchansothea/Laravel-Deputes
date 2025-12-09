<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersionalAccessToken extends Model
{
    use HasFactory;
    public $table="personal_access_tokens";
    public $timestamps=false;
    protected $guarded = [];
}
