<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailJobs extends Model
{
    use HasFactory;
    public $table="failed_jobs";
    public $timestamps=false;
    protected $guarded = [];
}
