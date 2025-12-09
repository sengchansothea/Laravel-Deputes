<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseLog6 extends Model
{
    use HasFactory;
    public $table="tbl_case_log6";
    public $timestamps=false;
    protected $guarded = [];

    public function getLog6DateAttribute($value){
        return date2Display($value);
    }

    /**
     * Get the log6 status
     */
    public function status(): HasOne
    {
        return $this->hasOne(Log6Status::class, "id", 'status_id');
    }

    /**
     * Get the log6 type
     */
    public function type(): HasOne
    {
        return $this->hasOne(Log6Type::class, "id", 'type_id');
    }

    /**
     * Get the case associated with this log6
     */
    public function case(): HasOne
    {
        return $this->hasOne(Cases::class, "id", 'case_id');
    }

    /**
     * Get all log620 records
     */
    public function log620(): HasMany
    {
        return $this->hasMany(CaseLog620::class, "case_id", 'case_id')
            ->where("log_id", $this->log_id);
    }

    /**
     * Get all log621 records
     */
    public function log621(): HasMany
    {
        return $this->hasMany(CaseLog621::class, "case_id", 'case_id')
            ->where("log_id", $this->log_id);
    }

    /**
     * Get the cause (log624)
     */
    public function log624(): HasOne
    {
        return $this->hasOne(Log624::class, "id", 'log624_cause_id');
    }

    /**
     * Get the solution (log625)
     */
    public function log625(): HasOne
    {
        return $this->hasOne(Log625::class, "id", 'log625_solution_id');
    }

    /**
     * Get all attendants for this log
     */
    public function attendant(): HasMany
    {
        return $this->hasMany(CaseLogAttendant::class, "log_id", 'log_id');
    }

    /**
     * Get the conflict officer (attendant_type_id = 6)
     */
    public function conflictOfficer(): HasOne
    {
        return $this->hasOne(CaseLogAttendant::class, "log_id", 'log_id')
            ->where("attendant_type_id", "=", 6);
    }

    /**
     * Get the conflict noter (attendant_type_id = 8)
     */
    public function conflictNoter(): HasOne
    {
        return $this->hasOne(CaseLogAttendant::class, "log_id", 'log_id')
            ->where("attendant_type_id", "=", 8);
    }

}
