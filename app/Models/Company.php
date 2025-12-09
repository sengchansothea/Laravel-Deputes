<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    public $table="tbl_company";
    public $timestamps=false;
    protected $guarded = [];

    public function cases()
    {
        return $this->hasMany(Cases::class, "company_id", 'id');
    }

    public function companyType(){
        return $this->hasOne(CompanyType::class, "id", 'company_type_id');
    }
    
    public function companyArticle()
    {
        return $this->hasOne(ArticleOfCompany::class, "id", 'article_of_company');
    }
    
    public function subSector(){
        return $this->hasOne(SubSector::class, "id", 'sub_sector_id');
    }

    public function businessActivity(){
        return $this->hasOne(BusinessActivity::class, "id", 'business_activity');
    }
    public function businessActivity1(){
        return $this->hasOne(NeaIsic::class, "isic_code", 'business_activity1');
    }
    public function businessActivity2(){
        return $this->hasOne(NeaIsic::class, "isic_code", 'business_activity2');
    }
    public function businessActivity3(){
        return $this->hasOne(NeaIsic::class, "isic_code", 'business_activity3');
    }
    public function businessActivity4(){
        return $this->hasOne(NeaIsic::class, "isic_code", 'business_activity4');
    }

    public function cSIC1()
    {
        return $this->belongsTo(Csic::class, 'csic_1', 'csic_1')
            ->whereNull('csic_2');
    }

    public function cSIC2()    {
        return $this->belongsTo(Csic::class, 'csic_2', 'csic_2')
                ->whereNull('csic_3');
    }
    public function cSIC3()
    {
        return $this->belongsTo(Csic::class, 'csic_3', 'csic_3')
            ->whereNull('csic_4');
    }
    public function cSIC4()
    {
        return $this->belongsTo(Csic::class, 'csic_4', 'csic_4')
            ->whereNull('csic_5');
    }

    public function cSIC5()
    {
        return $this->belongsTo(Csic::class, 'csic_5', 'csic_5')
            ->whereNotNull('csic_5');
    }



//    public function cSIC1(){
//        return $this->hasOne(Csic::class, "csic_1", 'csic_1');
//    }
//    public function cSIC2(){
//        return $this->hasOne(Csic::class, "csic_2", 'csic_2');
//    }
//    public function cSIC3(){
//        return $this->hasOne(Csic::class, "csic_3", 'csic_3');
//    }
//    public function cSIC4(){
//        return $this->hasOne(Csic::class, "csic_4", 'csic_4');
//    }
//    public function cSIC5(){
//        return $this->hasOne(Csic::class, "csic_5", 'csic_5');
//    }

    public function village(){
        return $this->hasOne(Village::class, "vil_id", 'village_id');
    }

    public function commune(){
        return $this->hasOne(Commune::class, "com_id", 'commune_id');
    }

    public function district(){
        return $this->hasOne(District::class, "dis_id", 'district_id');
    }

    public function province(){
        return $this->hasOne(Province::class, "pro_id", 'province_id');
    }
}
