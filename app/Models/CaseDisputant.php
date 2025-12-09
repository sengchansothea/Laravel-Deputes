<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseDisputant extends Model
{
    use HasFactory;
    public $table="tbl_case_disputant";
    public $timestamps=false;
    protected $guarded = [];

    public function addressProvince()
    {
        return $this->hasOne(Province::class, "pro_id", 'province');
    }
    public function addressDistrict()
    {
        return $this->hasOne(District::class, "dis_id", 'district');
    }
    public function addressCommune()
    {
        return $this->hasOne(Commune::class, "com_id", 'commune');
    }
    public function addressVillage()
    {
        return $this->hasOne(Village::class, "vil_id", 'village');
    }

    public function disputant()
    {
        /** Get Current Info of Disputant */
        return $this->hasOne(Disputant::class, "id", 'disputant_id');
    }

    public function disputantType()
    {
        /** Get Current Info of Disputant */
        return $this->hasOne(CaseAttendantType::class, "id", 'disputant_type_id');
    }

}
