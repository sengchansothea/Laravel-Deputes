<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Officer extends Model
{
    use HasFactory;
    public $table="tbl_officer";
    public $timestamps=false;
    protected $guarded = [];

    /**
     * Get all case officers associated with this officer
     */
    public function casesOfficers(): HasMany
    {
        return $this->hasMany(CaseOfficer::class, "officer_id", 'id');
    }

    /**
     * Get case officers with attendant_type_id = 6 (solvers)
     */
    public function caseOfficerSolvers(): HasMany
    {
        return $this->hasMany(CaseOfficer::class, "officer_id", 'id')
                    ->where('attendant_type_id', 6);
    }

    /**
     * Get case officers with attendant_type_id = 8 (noters)
     */
    public function caseOfficerNoters(): HasMany
    {
        return $this->hasMany(CaseOfficer::class, "officer_id", 'id')
            ->where('attendant_type_id', 8);
    }

    /**
     * Get the officer role associated with this officer
     */
    public function officerRole(): HasOne
    {
        return $this->hasOne(OfficerRole::class, "id", 'officer_role_id');
    }

    /**
     * Get the user associated with this officer
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, "officer_id", 'id');
    }

}
