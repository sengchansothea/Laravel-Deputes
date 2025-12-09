<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseInvitation extends Model
{
    use HasFactory;
    public $table="tbl_case_invitation";
    public $timestamps=false;
    protected $guarded = [];

    public function type()
    {
        return $this->hasOne(InvitationType::class, "id", 'invitation_type_id');
    }
    public function case()
    {
        return $this->hasOne(Cases::class, "id", 'case_id');
    }
    public function invitationType()
    {
        return $this->hasOne(InvitationType::class, "id", 'invitation_type_id');
    }
    public function company()
    {
        return $this->hasOne(Company::class, "company_id", 'company_id');
    }
    public function caseCompany()
    {
        return $this->hasOne(CaseCompany::class, "case_id", 'case_id');
    }
    public function disputant()
    {
        return $this->hasOne(Disputant::class, "id", 'disputant_id');
    }

    public function receiveDisputant()
    {
        return $this->hasOne(Disputant::class, "id", 'receive_disputant_id');
    }
    public function caseDisputant()
    {
        return $this->hasOne(CaseDisputant::class, "case_id", 'case_id');
    }


    public function nextTime()
    {
        return $this->hasMany(InvitationNextTime::class, "invitation_id", 'id');
    }
    public function nextTimeLatest()
    {
        return $this->hasOne(InvitationNextTime::class, "invitation_id", 'id')
            ->orderBy("id", "DESC");
    }

    /** Data Entry */
    public function entryUser()
    {
        /** get attendant (person in case) by attendant_type */
        return $this->hasOne(User::class, "id", 'user_created');
    }

    /** User Who Updated The CaseInvitation*/
    public function entryUpdatedUser()
    {
        /** get attendant (person in case) by attendant_type */
        return $this->hasOne(User::class, "id", 'user_updated');
    }

}
