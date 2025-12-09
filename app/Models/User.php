<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
//    protected $fillable = [
//        'name',
//        'email',
//        'password',
//    ];
    protected $guarded = [];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];




    public function company()
    {
        return $this->hasOne(CompanyApi::class, "company_id", 'company_id');
    }
    public function province(){
        return $this->hasOne(Province::class, "pro_id", 'k_province');
    }

    public function role(){
        return $this->hasOne(Role::class, "id", 'role_id');
    }

    public function officer(){
        return $this->hasOne(Officer::class, "id", 'officer_id');
    }

    public function officerRole()
    {
        return $this->hasOneThrough(
            OfficerRole::class, // Final target
            Officer::class,     // Intermediate
            'id',               // Local key on Officer (Officer.id)
            'id',               // Local key on OfficerRole (OfficerRole.id)
            'officer_id',       // Foreign key on User (User.officer_id)
            'officer_role_id'   // Foreign key on Officer (Officer.officer_role_id)
        );
    }

    public function category(){
        return $this->hasOne(RolekParents::class, "id", "k_category");
    }



    public function isSuperUser(): bool {
        return $this->department_id == 0;
    }

    public function isInspectorUser(): bool {
        return $this->department_id == 0 || $this->department_id == 1;
    }
    public function isDoshUser(): bool {
        return $this->department_id == 0 || $this->department_id == 4;
    }
    public function isDisputeUser(): bool {
        return $this->department_id == 0 || $this->department_id == 6;
    }
    public function isEmploymentUser(): bool {
        return $this->department_id == 0 || $this->department_id == 7;
    }



    public function isOfficer(): bool {
        return $this->k_team == 0;
    }
    public function isCompany(): bool {
        return $this->k_team == 1;
    }

    public function isOfficerOrCompany(): bool {
        return $this->k_team == 0 || $this->k_team == 1;
    }





}
