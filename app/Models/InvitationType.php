<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationType extends Model
{
    use HasFactory;
    public $table="tbl_invitation_type";
    public $timestamps=false;
    protected $guarded = [];

    public function group()
    {
        return $this->hasOne(InvitationTypeGroup::class, "id", 'type_group_id');
    }

}
