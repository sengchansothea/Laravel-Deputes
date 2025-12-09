<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainDistrict extends Model
{
    use HasFactory;
    public $table = "tbl_domain_district";
    public $timestamps = false;
    protected $guarded = [];

    public function domainCommune()
    {
//        return $this->hasMany(DomainCommune::class, "district_id", 'district_id');
        return $this->hasMany(DomainCommune::class, 'district_id', 'district_id')
            ->where('domain_id', $this->domain_id);
    }

    public function domain()
    {
        return $this->hasOne(DomainName::class, "id", 'domain_id');
    }

    public function province()
    {
        return $this->hasOne(Province::class, "pro_id", 'province_id')->orderBy('pro_id', 'ASC');
    }

    public function district()
    {
        return $this->hasOne(District::class, "dis_id", 'district_id');
    }

    public function commune()
    {
//        return $this->hasOne(Commune::class, "com_id", 'commune_id');
        return $this->hasMany(Commune::class, 'district_id', 'district_id');
    }


}
