<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cases extends Model
{
    use HasFactory;
    public $table="tbl_case";
    public $timestamps=false;
    protected $guarded = [];

    public function collectivesIssues()
    {
        return $this->hasMany(CollectivesIssues::class, "case_id", 'id');
    }

    public function collectivesCaseDisputants() // List all representatives in case with detail info
    {
        return $this->hasMany(CaseDisputant::class, "case_id", 'id');
    }

    public function collectivesCaseDisputantsEmp() // List all representatives of Collectives Employees
    {
        return $this->hasMany(CaseDisputant::class, "case_id", 'id')
                    ->where('attendant_type_id', 1);
    }

    public function collectivesCaseDisputantsCom() // List all representatives Of Collectives Company (តំណាងក្រុមហ៊ុន និង អ្នកអម)
    {
        return $this->hasMany(CaseDisputant::class, "case_id", 'id')
                    ->where('attendant_type_id', 3)
                    ->orwhere('attendant_type_id', 4);
    }

    public function collectivesRepresentatives() // List all representatives in case without detail info
    {
        return $this->hasMany(CollectivesRepresentatives::class, "case_id", 'id');
    }

    public function caseClosedStep()
    {
        return $this->hasOne(CaseSteps::class, "id", 'case_closed_step_id');
    }
    public function caseClosedCause()
    {
        return $this->hasOne(Log624::class, "id", 'case_cause_id');
    }
    public function caseClosedSolution()
    {
        return $this->hasOne(Log625::class, "id", 'case_solution_id');
    }
    public function caseAllOfficers(): HasMany
    {
        return $this->hasMany(CaseOfficer::class, "case_id", 'id');
    }

    public function caseNoter(): HasOne
    {
        return $this->hasOne(CaseOfficer::class, "case_id", 'id')
                    ->where("attendant_type_id", "=", 8);
    }

    public function latestCaseOfficer(): HasOne
    {
        return $this->hasOne(CaseOfficer::class, 'case_id', 'id')
                ->where("attendant_type_id", "=", 6)
                ->latest('date_created');
    }

    public function caseOfficer(): HasOne
    {
        return $this->hasOne(CaseOfficer::class, "case_id", 'id');
    }

    public function lastOfficer(): HasMany
    {
        return $this->hasMany(CaseOfficer::class, "case_id", 'id')
            ->latest();
    }

    public function lastCaseOfficer(): HasOne
    {
        return $this->hasOne(CaseOfficer::class, 'case_id', 'id')
            ->where('attendant_type_id', 6)
            ->latest();
    }

    public function lastCaseNoter(): HasOne
    {
        return $this->hasOne(CaseOfficer::class, 'case_id', 'id')
            ->where('attendant_type_id', 8)
            ->latest();
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
        return $this->hasOne(CaseDisputant::class, "case_id", 'id')
                    ->whereColumn("disputant_id", "disputant_id");


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

    public function caseCompanyX()
    {
        /** Get More Company Info in Case */
        return $this->hasOne(CaseCompany::class, "case_id", "id")
                ->where("company_id", $this->company_id);
    }

    public function caseCompany()
    {
        return $this->hasOne(CaseCompany::class, "case_id", "id")
            ->whereColumn("company_id", "company_id");
    }


    public function casesCompany()
    {
        /** Get More Company Info in Case */
        return $this->hasOne(CaseCompany::class, "case_id", "id");

    }

    public function caseDomain(){
        return $this->hasOne(CaseCompany::class, "case_id", "id");
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
            ->whereIn("invitation_type_id", [5, 6]);
    }

    public function collectivesInvitationForConcilation()
    {
        return $this->hasMany(CaseInvitation::class, "case_id", 'id')
            ->whereIn("invitation_type_id", [9, 10]);
    }
    public function collectivesInvForConcilationEmp()
    {
        return $this->hasOne(CaseInvitation::class, "case_id", 'id')
            ->where("invitation_type_id", "=", 9);
    }
    public function collectivesInvForConcilationCom()
    {
        return $this->hasOne(CaseInvitation::class, "case_id", 'id')
            ->where("invitation_type_id", "=", 10);
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
    public function invitationCollectivesDisputants() // លិខិតអញ្ញើញកម្មករ ក្នុងវិវាទការងាររួម
    {
        return $this->hasOne(CaseInvitation::class, "case_id", 'id')
                    ->where("invitation_type_id", 7);
    }

    public function invitationCollectivesCompany() //លិខិតអញ្ញើញក្រុមហ៊ុន ក្នុងវិវាទការងាររួម
    {
        return $this->hasOne(CaseInvitation::class, "case_id", 'id')
            ->where("invitation_type_id", 8);
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

    public function log(): HasMany
    {
        return $this->hasMany(CaseLog::class, "case_id", 'id');
    }

    public function log345(): HasMany
    {
        return $this->hasMany(CaseLog::class, "case_id", 'id')
            ->where("log_type_id", "<", 6);
    }

    public function log34Detail(): HasOne
    {
        return $this->hasOne(CaseLog34::class, "case_id", 'id');
    }

    public function log5Detail(): HasOne
    {
        return $this->hasOne(CaseLog5::class, "case_id", 'id');
    }

    public function log6Detail(): HasOne
    {
        return $this->hasOne(CaseLog6::class, "case_id", 'id');
    }

    public function latestLog6Detail(): HasOne
    {
        return $this->hasOne(CaseLog6::class, 'case_id', 'id')
            ->latestOfMany('id');
    }

    public function log34(): HasMany
    {
        return $this->hasMany(CaseLog::class, "case_id", 'id')
            ->where("log_type_id", 3);
    }

    public function log5(): HasMany
    {
        return $this->hasMany(CaseLog::class, "case_id", 'id')
            ->where("log_type_id", 5);
    }

    public function log6(): HasMany
    {
        return $this->hasMany(CaseLog::class, "case_id", 'id')
            ->where("log_type_id", 6);
    }

    public function log6Latest(): HasOne
    {
        return $this->hasOne(CaseLog::class, "case_id", 'id')
            ->where("log_type_id", 6)
            ->orderBy("id", "DESC");
    }

    public function latestLog6(): HasOne
    {
        return $this->hasOne(CaseLog6::class, "case_id", 'id')
            ->latest('id');
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

    /** Data Entry */
    public function entryUser()
    {
        /** get attendant (person in case) by attendant_type */
        return $this->hasOne(User::class, "id", 'user_created');
    }

    /** User Who Updated The Case*/
    public function entryUpdatedUser()
    {
        /** get attendant (person in case) by attendant_type */
        return $this->hasOne(User::class, "id", 'user_updated');
    }

    public function caseObjective(){
        return $this->hasOne(CaseObjective::class, "id", 'case_objective_id');
    }




}
