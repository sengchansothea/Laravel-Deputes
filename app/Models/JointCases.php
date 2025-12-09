<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JointCases extends Model
{
    use HasFactory;
    public $table="tbl_joint_disute";
    public $timestamps=false;
    protected $guarded = [];


    public function unit()
    {
        return $this->hasOne(Unit::class, "id", 'unit_id');
    }

    public function caseType()
    {
        return $this->hasOne(CaseType::class, "id", 'case_type_id');
    }
    public function disputant()
    {
        /** Get Main Info of Disputant */
        return $this->hasOne(Disputant::class, "id", 'disputant_id');
    }
    public function caseDisputant()
    {
        /** Get More Info of Disputant in Case */
        return $this->hasOne(CaseDisputant::class, "case_id", 'id');
    }
    public function companyApi()
    {
        /** Get Main Company Info From LACMS if The Company Exists in LACMS */
        return $this->hasOne(CompanyApi::class, "company_id", 'company_id');
    }
    public function company()
    {
        /** Get Main Company Info From Inside Company if The Company NOT Exists in LACMS */
        return $this->hasOne(Company::class, "company_id", 'company_id');
    }
    public function companyType()
    {
        return $this->hasOne(CompanyType::class, "id", 'company_type_id');
    }
    public function caseSector(){
        return $this->hasOne(Sector::class, "id", 'sector_id');
    }

    public function companyBusinessActivity()
    {
        return $this->hasOne(BusinessActivity::class, "id", 'business_activity');
    }

    public function caseCompany()
    {
        /** Get More Company Info in Case */
        return $this->hasOne(CaseCompany::class, "case_id", "id")
            ->where("company_id", $this->company_id);
    }

    public function invitationAll()
    {
        return $this->hasMany(CaseInvitation::class, "case_id", 'id');
    }
    public function invitationForGiveInfo()
    {
        return $this->hasMany(CaseInvitation::class, "case_id", 'id')
            ->where("invitation_type_id", "<", 5);
    }
    public function invitationForConcilation()
    {
        return $this->hasMany(CaseInvitation::class, "case_id", 'id')
            ->where("invitation_type_id", ">", 4);
    }
    public function invitationForConcilationEmployee()
    {
        return $this->hasOne(CaseInvitation::class, "case_id", 'id')
            ->where("invitation_type_id", "=", 5);
    }
    public function invitationForConcilationCompany()
    {
        return $this->hasOne(CaseInvitation::class, "case_id", 'id')
            ->where("invitation_type_id", "=", 6);
    }
    public function invitationDisputant()
    {
        return $this->hasOne(CaseInvitation::class, "case_id", 'id')
            ->where("invitation_type_id", 1)
            ->orWhere("invitation_type_id", 4);
    }
    public function invitationCompany()
    {
        return $this->hasOne(CaseInvitation::class, "case_id", 'id')
            ->where("invitation_type_id", 2)
            ->orWhere("invitation_type_id", 3);
    }

    public function log()
    {
        return $this->hasMany(CaseLog::class, "case_id", 'id');
    }
    public function log345()
    {
        return $this->hasMany(CaseLog::class, "case_id", 'id')
            ->where("log_type_id", "<", 6);
    }

    public function log34Detail()
    {// IF Log34 Have Only One Record
        return $this->hasOne(CaseLog34::class, "case_id", 'id');
    }
    public function log5Detail()
    {// IF Log5 Have Only One Record
        return $this->hasOne(CaseLog5::class, "case_id", 'id');
    }
    public function log6Detail()
    { // IF Log6 Have Only One Record
        return $this->hasOne(CaseLog6::class, "case_id", 'id');
    }
    public function log34()
    {// IF Log34 Have Many Record
        return $this->hasMany(CaseLog::class, "case_id", 'id')
            ->where("log_type_id", 3)
            ;
    }
    public function log5()
    {// IF Log5 Have Many Record
        return $this->hasMany(CaseLog::class, "case_id", 'id')
            ->where("log_type_id", 5);
    }
    public function log6()
    {// IF Log6 Have Many Record
        return $this->hasMany(CaseLog::class, "case_id", 'id')
            ->where("log_type_id", 6);
    }
    public function log6Latest()
    {
        return $this->hasOne(CaseLog::class, "case_id", 'id')
            ->where("log_type_id", 6)
            ->orderBy("id", "DESC");
    }

    public function logAttendantAll()
    {
        /** get all attendant person */
        return $this->hasMany(CaseLogAttendant::class, "case_id", 'id');
    }
    public function logAttendantDisputant()
    {
        /** get attendant disputant only */
        return $this->hasMany(CaseLogAttendant::class, "case_id", 'id')
            ->where("attendant_type", "<", 6);

    }
    public function logAttendanOfficer()
    {
        /** get attendant officer only */
        return $this->hasOne(CaseLogAttendant::class, "case_id", 'id')
            ->where("attendant_type", ">", 5);

    }
    public function logAttendant($attendant_type = 1)
    {
        /** get attendant (person in case) by attendant_type */
        return $this->hasOne(CaseLogAttendant::class, "case_id", 'id')
            ->where("attendant_type", "", $attendant_type);
    }



}
