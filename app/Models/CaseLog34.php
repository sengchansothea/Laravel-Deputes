<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseLog34 extends Model
{
    use HasFactory;
    public $table="tbl_case_log34";
    public $timestamps=false;
    protected $guarded = [];

    public function case()
    {
        return $this->hasOne(Cases::class, "id", 'case_id');
    }
    public function invitation()
    {
        return $this->hasOne(CaseInvitation::class, "id", 'invitation_id');
    }

    public function attendant()
    {
        return $this->hasMany(CaseLogAttendant::class, "log_id", 'log_id');
    }

    public function collectivesRepresentatives() // List all attendants in log34
    {
        return $this->hasMany(CaseLogAttendant::class, "log_id", 'log_id')
                    ->where('case_id', $this->case_id)
                    ->where('attendant_type_id', 1); // ដើមបណ្តឹង
    }

    public function noter(){
        return $this->hasOne(CaseLogAttendant::class, "log_id", 'log_id')
            ->where("attendant_type_id", "=", 8);
    }

    public function headMeeting(){
        return $this->hasOne(CaseLogAttendant::class, "log_id", 'log_id')
            ->where("attendant_type_id", "=", 6);
    }

    public function log34Issues()
    {
        return $this->hasMany(CollectivesLog34Issues::class, "log_id", 'log_id');
    }

    /** Data Entry */
    public function entryUser()
    {
        /** get attendant (person in case) by attendant_type */
        return $this->hasOne(User::class, "id", 'user_created');
    }

    /** User Who Updated CaseLog34*/
    public function entryUpdatedUser()
    {
        /** get attendant (person in case) by attendant_type */
        return $this->hasOne(User::class, "id", 'user_updated');
    }


}
