<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpesLocCD extends Model
{
    use HasFactory;
    public $table = "CPES_LOC_CD";
    protected $primaryKey = 'LOC_SEQ';
    public $timestamps = false;
    // protected $guarded = [];
    protected $fillable = [
        'LVL_CD', 'LACMS_PRO_ID', 'LACMS_DIS_ID', 'LACMS_COM_ID', 'LACMS_VIL_ID',
        'COUNTRY_ID', 'PROVINCE_ID', 'DISTRICT_ID', 'COMMUNE_ID', 'VILLAGE_ID',
        'PARENT_ID', 'LVL', 'NM_EN', 'NM_KH', 'PROVINCE_ID_API', 'ADDR_FULL_CD'
    ];
}
