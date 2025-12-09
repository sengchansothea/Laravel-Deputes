<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectivesLog34Issues extends Model
{
    use HasFactory;
    public $table = "tbl_collectives_log34_issues";
    public $timestamps=false;
    protected $guarded = [];

    public function log5Provided(){
        return $this->hasOne(CollectivesLog5Provided::class, 'issue_id', 'id');
    }


}
