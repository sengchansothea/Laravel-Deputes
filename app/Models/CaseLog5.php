<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseLog5 extends Model
{
    use HasFactory;
    public $table="tbl_case_log5";
    public $timestamps=false;
    protected $guarded = [];

    public function case()
    {
        return $this->hasOne(Cases::class, "id", 'case_id');
    }

    public function union1()
    {
        return $this->hasMany(CaseLog5Union1::class, "case_id", 'case_id');
    }

    public function attendant()
    {
        return $this->hasMany(CaseLogAttendant::class, "log_id", 'log_id');
    }


    public function headMeeting(){
        return $this->hasOne(CaseLogAttendant::class, "log_id", 'log_id')
            ->where("attendant_type_id", "=", 6);
    }

    public function noter(){
        return $this->hasOne(CaseLogAttendant::class, "log_id", 'log_id')
            ->where("attendant_type_id", "=", 8);
    }

    /** Data Entry */
    public function entryUser()
    {
        /** get attendant (person in case) by attendant_type */
        return $this->hasOne(User::class, "id", 'user_created');
    }

    /** User Who Updated CaseLog5*/
    public function entryUpdatedUser()
    {
        /** get attendant (person in case) by attendant_type */
        return $this->hasOne(User::class, "id", 'user_updated');
    }

}
