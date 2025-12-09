<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationTypeGroup extends Model
{
    use HasFactory;
    public $table="tbl_invitation_type_group";
    public $timestamps=false;
    protected $guarded = [];
}
