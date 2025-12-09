<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainCommune extends Model
{
    use HasFactory;
    public $table = "tbl_domain_commune";
    public $timestamps = false;
    protected $guarded = [];

    public function province()
    {
        return $this->hasOne(Province::class, "pro_id", 'province_id');
    }
    public function district()
    {
        return $this->hasOne(District::class, "dis_id", 'district_id');
    }
    public function commune()
    {
        return $this->hasOne(Commune::class, "com_id", 'commune_id');

    }
}
