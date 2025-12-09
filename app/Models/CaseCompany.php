<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseCompany extends Model
{
    use HasFactory;
    public $table="tbl_case_company";
    public $timestamps=false;
    protected $guarded = [];

    public function province()
    {
        return $this->hasOne(Province::class, "pro_id", 'log5_province_id');
    }
    public function district()
    {
        return $this->hasOne(District::class, "dis_id", 'log5_district_id');
    }
    public function commune()
    {
        return $this->hasOne(Commune::class, "com_id", 'log5_commune_id');
    }
    public function village()
    {
        return $this->hasOne(Village::class, "vil_id", 'log5_village_id');
    }

    public function nationalityOwner()
    {
        return $this->hasOne(Nationality::class, "id", 'log5_owner_nationality_id');
    }
    public function nationalityDirector()
    {
        return $this->hasOne(Nationality::class, "id", 'log5_director_nationality_id');
    }
    public function companyArticle()
    {
        return $this->hasOne(ArticleOfCompany::class, "id", 'log5_article_of_company');
    }
    public function companyType()
    {
        return $this->hasOne(CompanyType::class, "id", 'log5_company_type_id');
    }

    public function subSector(){
        return $this->hasOne(SubSector::class, "id", 'log5_sub_sector_id');
    }

    public function businessActivity()
    {
        return $this->hasOne(BusinessActivity::class, "id", 'log5_business_activity');
    }

    public function domainProvince()
    {
        return $this->hasMany(DomainProvince::class, 'province_id', 'log5_province_id');
//            ->where('domain_id', $this->domain_id);
    }

    public function domainDistrict()
    {
        return $this->hasMany(DomainDistrict::class, 'province_id', 'log5_province_id')
            ->where('district_id', $this->log5_district_id);
    }
    public function domainCommune()
    {
        return $this->hasMany(DomainCommune::class, 'province_id', 'log5_province_id')
            ->where('district_id', $this->log5_district_id)
            ->where('commune_id', $this->log5_commune_id);
    }

    public function csic1()
    {
        return $this->hasOne(Csic::class, "csic_1", 'log5_csic_1');
    }
    public function csic2(){
        return $this->hasOne(Csic::class, "csic_2", 'log5_csic_2')
            ->whereNull('csic_3');
    }
    public function csic3(){
        return $this->hasOne(Csic::class, "csic_3", 'log5_csic_3')
            ->whereNull('csic_4');
    }
    public function csic4(){
        return $this->hasOne(Csic::class, "csic_4", 'log5_csic_4')
            ->whereNull('csic_5');
    }
    public function csic5(){
        return $this->hasOne(Csic::class, "csic_5", 'log5_csic_5');
    }
}
