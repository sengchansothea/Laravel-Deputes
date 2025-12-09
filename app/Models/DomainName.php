<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainName extends Model
{
    use HasFactory;
    public $table = "tbl_domain_name";
    public $timestamps = false;
    protected $guarded = [];

    public function domainProvince()
    {
        return $this->hasMany(DomainProvince::class, "domain_id", 'id')->orderBy('sort_by');
    }

    public function domainDistrict()
    {
//        return $this->hasMany(DomainDistrict::class, "domain_id", 'id');
        return $this->hasMany(DomainDistrict::class, 'province_id', 'province_id')
            ->where('domain_id', $this->domain_id);
    }
    public function domainCommune()
    {
        return $this->hasMany(DomainCommune::class, "domain_id", 'id');
    }

    public function domainDistinctDistrictCommune()
    {
        return $this->hasMany(DomainCommune::class, "domain_id", 'id')
            ->select('district_id')->distinct('district_id');
    }

    public function officerRoles()
    {
        return $this->hasMany(OfficerRole::class, "domain_id", 'id');
    }

}
