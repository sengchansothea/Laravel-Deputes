<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationNextTime extends Model
{
    use HasFactory;
    public $table="tbl_case_invitation_next_time";
    public $timestamps=false;
    protected $guarded = [];

    public function status()
    {
        return $this->hasOne(NextTimeStatus::class, "id", 'status_id');
    }
}
