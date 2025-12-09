<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CaseOfficer extends Model
{
    use HasFactory;
    public $table="tbl_case_officer";
    public $timestamps=false;
    protected $guarded = [];

    /**
     * Get the officer associated with this case officer record
     */
    public function officer(): HasOne
    {
        return $this->hasOne(Officer::class, "id", 'officer_id');
    }

    /**
     * Get the case associated with this case officer record
     */
    public function case(): HasOne
    {
        return $this->hasOne(Cases::class, "id", 'case_id');
    }

}
