<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainProvince extends Model
{
    use HasFactory;
    public $table = "tbl_domain_province";
    public $timestamps = false;
    protected $guarded = [];

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
//        return $this->hasOne(District::class, "dis_id", 'district_id');
        return $this->hasMany(District::class, 'dis_id', 'district_id');
    }

    public function domainDistrict()
    {
//        return $this->hasMany(DomainDistrict::class, "province_id", 'province_id');
        return $this->hasMany(DomainDistrict::class, 'province_id', 'province_id')
            ->where('domain_id', $this->domain_id);
    }
}
