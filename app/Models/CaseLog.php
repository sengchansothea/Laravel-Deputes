<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseLog extends Model
{
    use HasFactory;
    public $table = "tbl_case_log";
    public $timestamps = false;
    protected $guarded = [];

    /**
     * Get the log34 detail associated with this case log
     */
    public function detail34(): HasOne
    {
        return $this->hasOne(CaseLog34::class, "case_id", "case_id")
            ->where("log_id", $this->id);
    }

    /**
     * Get the log5 detail associated with this case log
     */
    public function detail5(): HasOne
    {
        return $this->hasOne(CaseLog5::class, "case_id", "case_id")
            ->where("log_id", $this->id);
    }

    /**
     * Get the log6 detail associated with this case log
     */
    public function detail6(): HasOne
    {
        return $this->hasOne(CaseLog6::class, 'log_id', 'id');
    }

    /**
     * Get all log6 records for this case log
     */
    public function log6xx(): HasMany
    {
        return $this->hasMany(CaseLog6::class, "case_id", 'case_id');
    }

}
