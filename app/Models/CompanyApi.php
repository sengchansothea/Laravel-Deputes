<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyApi extends Model
{
    use HasFactory;
    public $table="tbl_company_api";
    public $timestamps=false;
    protected $guarded = [];

    public function subSector(){
        return $this->hasOne(SubSector::class, "id", 'sub_sector_id');
    }

    public function businessActivity()
    {
        return $this->hasOne(BusinessActivity::class, "id", 'business_activity');
    }
    public function companyType()
    {
        return $this->hasOne(CompanyType::class, "id", "type_of_company"  );
    }


    public function province()
    {
        return $this->hasOne(Province::class, "pro_id", 'business_province');
    }
    public function district()
    {
        return $this->hasOne(District::class, "dis_id", 'business_district');
    }
    public function commune()
    {
        return $this->hasOne(Commune::class, "com_id", 'business_commune');
    }
    public function village()
    {
        return $this->hasOne(Village::class, "vil_id", 'business_village');
    }
    public function provinceEnterprise()
    {
        return $this->hasOne(Province::class, "pro_id", 'enterprise_province');
    }
    public function districtEnterprise()
    {
        return $this->hasOne(District::class, "dis_id", 'enterprise_district');
    }
    public function communeEnterprise()
    {
        return $this->hasOne(Commune::class, "com_id", 'enterprise_commune');
    }
    public function villageEnterprise()
    {
        return $this->hasOne(Village::class, "vil_id", 'enterprise_village');
    }

    public function ownerNationality()
    {
        return $this->hasOne(Nationality::class, "id", 'owner_nationality');
    }
    public function directorNationality()
    {
        return $this->hasOne(Nationality::class, "id", 'director_nationality');
    }

}
