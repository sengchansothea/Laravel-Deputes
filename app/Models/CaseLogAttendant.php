<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseLogAttendant extends Model
{
    use HasFactory;
    public $table="tbl_case_log_attendant";
    public $timestamps=false;
    protected $guarded = [];

    public function disputant()
    {
        return $this->hasOne(Disputant::class, "id", 'attendant_id');
    }
    public function caseDisputant()
    {
        /** Get More Info of Disputant in Case */
        return $this->hasOne(CaseDisputant::class, "case_id", 'case_id')
                ->where("disputant_id", $this->attendant_id);
    }
    public function officer()
    {
        return $this->hasOne(Officer::class, "id", 'attendant_id');
    }
    public function type()
    {
        return $this->hasOne(CaseAttendantType::class, "id", 'attendant_type_id');
    }

}
