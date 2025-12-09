<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disputant extends Model
{
    use HasFactory;
    public $table="tbl_disputant";
    public $timestamps=false;
    protected $guarded = [];

    public function case()
    {
        return $this->hasMany(Cases::class, "disputant_id", 'id');
    }

    public function getDobAttribute($value){
        return date2Display($value);
    }

    public function pobProvince()
    {
        return $this->hasOne(Province::class, "pro_id", 'pob_province_id');
    }
    public function pobDistrict()
    {
        return $this->hasOne(District::class, "dis_id", 'pob_district_id');
    }
    public function pobCommune()
    {
        return $this->hasOne(Commune::class, "com_id", 'pob_commune_id');
    }
    
    public function nowProvince()
    {
        return $this->hasOne(Province::class, "pro_id", 'province');
    }
    public function nowDistrict()
    {
        return $this->hasOne(District::class, "dis_id", 'district');
    }
    public function nowCommune()
    {
        return $this->hasOne(Commune::class, "com_id", 'commune');
    }
    public function nowVillage()
    {
        return $this->hasOne(Village::class, "vil_id", 'village');
    }

    public function disNationality(){
        return $this->hasOne(Nationality::class, "id", 'nationality');
    }
    
    public function pobAbroad(){
        return $this->hasOne(Nationality::class, "id", 'pob_country_id');
    }

}
