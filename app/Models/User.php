<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'last_name',
        'first_name',
        'middle_name',
        'name_suffix',
        'name',
        'email',
        'password',
        'is_admin',
        'company_id',
    ];

    protected $appends = ['userrole', 'createddate', 'admin', 'superadmin', 'role', 'PermissionList', 'EmployerRole', 'DolStaffRole'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getUserRoleAttribute()
    {
        $user = User::find($this->id);
        $roles = $user->roles;
        $data = [];
        foreach ($roles  as $k => $v) {
            $data[] = $v['name'];
        }
        $userrole = implode(',', $data);
        return  $userrole;
    }

    public function getcreateddateAttribute()
    {
        return Carbon::parse($this->created_at)->dayName . " " . Carbon::parse($this->created_at)->isoFormat(', MMM Do YYYY hh:mm A');
    }

    public function getsuperadminAttribute()
    {
        $user = User::find($this->id);
        $roles = $user->roles;
        $superadmin = 0;
        foreach ($roles  as $k => $v) {
            if ($v['id'] == ROLE::SUPERADMIN) {
                $superadmin = 1;
            }
        }
        return  $superadmin;
    }

    public function getadminAttribute()
    {
        $user = User::find($this->id);
        $roles = $user->roles;
        $admin = 0;
        foreach ($roles  as $k => $v) {
            if ($v['id'] == Role::ADMIN) {
                $admin = 1;
            }
        }
        return  $admin;
    }

    public function getroleAttribute()
    {
        $user = User::find($this->id);
        $roles = $user->roles;
        $role = 0;
        foreach ($roles  as $k => $v) {
            if ($v['id'] != ROLE::SUPERADMIN || $v['id'] != ROLE::ADMIN) {
                $role = $v['id'];
            }
        }
        return  $role;
    }


    public function getEmployerRoleAttribute()
    {
        $user = User::find($this->id);
        $roles = $user->roles;
        $role = 0;
        foreach ($roles  as $k => $v) {
            if ($v['id'] == ROLE::EMPLOYER) {
                $role = 1;
            }
        }
        return  $role;
    }


    public function getDolStaffRoleAttribute()
    {
        $user = User::find($this->id);
        $roles = $user->roles;
        $role = 0;
        foreach ($roles  as $k => $v) {
            if ($v['id'] == ROLE::DOLESTAFF) {
                $role = 1;
            }
            if ($v['id'] == Role::ADMIN) {
                $role = 1;
            }
        }
        return  $role;
    }

    public function getPermissionListAttribute()
    {
        $permissions = self::getPermissionNames();
        return $permissions;
    }

    public function employer()
    {
        return $this->hasOne(Employer::class, 'user_id', 'id');
    }

    public function dole()
    {
        return $this->hasOne(DolStaff::class, 'user_id', 'id');
    }
}
