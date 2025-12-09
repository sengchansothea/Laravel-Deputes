<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectivesIssues extends Model
{
    use HasFactory;
    public $table = "tbl_collectives_issues";
    public $timestamps=false;
    protected $guarded = [];
}
