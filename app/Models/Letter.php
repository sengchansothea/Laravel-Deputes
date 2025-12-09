<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Letter extends Model
{
    use HasFactory;
    
    public $table = "tbl_letter";
    public $timestamps = false;
    protected $guarded = [];

    /**
     * Get the company associated with this letter
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
