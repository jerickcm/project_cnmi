<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Logs extends Model
{
    protected $table = "logs";

    const TYPE_CREATE_NOTE = 1;
    const TYPE_DELETE_NOTE = 2;
    const TYPE_CREATE_COMPANY = 3;
    const TYPE_LOGIN = 4;
    const TYPE_LOGOUT = 5;

    const TYPE_DELETE_USER = 6;
    const TYPE_CREATE_USER = 7;
    const TYPE_UPDATE_USER = 8;
    const TYPE_CHANGEPASSWORD_USER = 9;
    const TYPE_RESETPASSWORD_USER = 10;

    const TYPE_UPDATE_CATEGORY = 11;
    const TYPE_UPDATE_COMPANY = 12;
    const TYPE_COMPANY_DELETE  = 13;
    const TYPE_COMPANY_EDIT  = 14;
    const TYPE_UPDATE_ROLES = 15;

    const TYPE_DELETE_EMPLOYER = 16;
    const TYPE_CREATE_EMPLOYER = 17;
    const TYPE_UPDATE_EMPLOYER = 18;
    const TYPE_UPLOAD_EMPLOYER = 19;
    const TYPE_WFPLAN_EDIT = 20;

    const TYPE_WFPLAN_TALLY_EDIT = 21;
    const TYPE_WFLIST_EDIT = 22;
    const TYPE_WFLIST_TALLY_EDIT = 23;
    const TYPE_BUSINESSINDUSTRY_DELETE = 24;
    const TYPE_BUSINESSTYPE_DELETE = 25;

    const TYPE_BUSINESS_INDUSTRY_EDIT = 26;
    const TYPE_BUSINESS_TYPE_EDIT = 27;
    const TYPE_CREATE_CATEGORY_BUSINESS_TYPE = 28;
    const TYPE_CREATE_CATEGORY_BUSINESS_INDUSTRY = 29;

    const TYPE_CREATE_NOTE_CLEARANCE = 30;
    const TYPE_DELETE_NOTE_CLEARANCE = 31;

    protected $fillable = [
        'user_id',
        'type',
        'meta',
    ];

    protected $appends = [
        'description', 'type_desc', 'username', 'useremail', 'createddate'
    ];

    public function getcreateddateAttribute()
    {
        return Carbon::parse($this->created_at)->dayName . " " . Carbon::parse($this->created_at)->isoFormat(', MMM Do YYYY hh:mm A');
    }

    public function getUserEmailAttribute()
    {
        return  User::select('email')->where('id', $this->user_id)->first()['email'];
    }

    public function getUserNameAttribute()
    {
        return  User::select('name')->where('id', $this->user_id)->first()['name'];
    }

    public function getMetaAttribute($value)
    {
        return json_decode($value);
    }

    public function setMetaAttribute($value)
    {
        $this->attributes['meta'] = json_encode($value);
    }


    public function getTypeDescAttribute($value)
    {
        switch ($this->attributes['type']) {
            case 1:
                $result = 'Create Note';
                break;

            case 2:
                $result = 'Delete Note';
                break;

            case 3:
                $result = 'Create Company';
                break;

            case 4:
                $result = 'User Login';
                break;
            case 5:
                $result = 'User Logout';
                break;
            case 6:
                $result = 'Delete User';
                break;
            case 7:
                $result = 'Create User';
                break;
            case 8:
                $result = 'Update User';
                break;
            case 9:
                $result = 'Change Password User';
                break;
            case 10:
                $result = 'Reset Password User';
                break;

            case 11:
                $result = 'Category Company Update';
                break;

            case 12:
                $result = 'Company Update';
                break;

            case 13:
                $result = 'Company Delete';
                break;

            case 14:
                $result = 'Company Edit';
                break;

            case 15:
                $result = 'Update Roles';
                break;

            case 16:
                $result = 'Delete Employer';
                break;
            case 17:
                $result = 'Create Employer';
                break;

            case 18:
                $result = 'Update Employer';
                break;

            case 19:
                $result = 'Upload ';
                break;

            case 20:
                $result = 'Edit WPLAN';
                break;
            case 21:
                $result = 'Edit WPLAN TALLY';
                break;
            case 22:
                $result = 'Edit LIST';
                break;
            case 23:
                $result = 'Edit LIST TALLY';
                break;
            case 24:
                $result = 'BUSINESS INDUSTRY DELETE';
                break;
            case 25:
                $result = 'BUSINESS TYPE DELETE';
                break;

            case 26:
                $result = 'BUSINESS INDUSTRY EDIT';
                break;
            case 27:
                $result = 'BUSINESS TYPE EDIT';
                break;
            case 28:
                $result = 'BUSINESS TYPE CREATE';
                break;
            case 29:
                $result = 'BUSINESS INDUSTRY CREATE';
                break;
            case 30:
                $result = 'CREATE NOTE CLEARANCE';
                break;
            case 31:
                $result = 'DELETE NOTE CLEARANCE';
                break;
        }
        return $result;
    }

    public function getDescriptionAttribute()
    {
        switch ($this->attributes['type']) {

            case 1:
                $result = __('logs.notes.create', json_decode($this->attributes['meta'], true));
                break;

            case 2:
                $result = __('logs.notes.delete', json_decode($this->attributes['meta'], true));
                break;

            case 3:
                $result = __('logs.company.create', json_decode($this->attributes['meta'], true));
                break;

            case 4:
                $result = __('logs.user.login', json_decode($this->attributes['meta'], true));
                break;
            case 5:
                $result = __('logs.user.logout', json_decode($this->attributes['meta'], true));
                break;
            case 6:
                $result = __('logs.user.delete', json_decode($this->attributes['meta'], true));
                break;
            case 7:
                $result = __('logs.user.create', json_decode($this->attributes['meta'], true));
                break;
            case 8:
                $result = __('logs.user.update', json_decode($this->attributes['meta'], true));
                break;

            case 9:
                $result = __('logs.user.changepassword', json_decode($this->attributes['meta'], true));
                break;
            case 10:
                $result = __('logs.user.resetpassword', json_decode($this->attributes['meta'], true));
                break;

            case 11:
                $result = __('logs.company.category_update', json_decode($this->attributes['meta'], true));
                break;


            case 12:
                $result = __('logs.company.update', json_decode($this->attributes['meta'], true));
                break;


            case 13:
                $result = __('logs.company.delete', json_decode($this->attributes['meta'], true));
                break;

            case 14:
                $result = __('logs.company.edit', json_decode($this->attributes['meta'], true));
                break;

            case 15:
                $result = __('logs.roles.update', json_decode($this->attributes['meta'], true));
                break;

            case 18:
                $result = __('logs.employer.update', json_decode($this->attributes['meta'], true));
                break;

            case 19:
                $result = __('logs.employer.upload', json_decode($this->attributes['meta'], true));
                break;

            case 20:
                $result = __('logs.workforce.edit_plan', json_decode($this->attributes['meta'], true));
                break;

            case 21:
                $result = __('logs.workforce.edit_plan_tally', json_decode($this->attributes['meta'], true));
                break;
            case 22:
                $result = __('logs.workforce.edit_list', json_decode($this->attributes['meta'], true));
                break;
            case 23:
                $result = __('logs.workforce.edit_list_tally', json_decode($this->attributes['meta'], true));
                break;
            case 24:
                $result = __('logs.category_business_industry.delete', json_decode($this->attributes['meta'], true));
                break;
            case 25:
                $result = __('logs.category_business_type.delete', json_decode($this->attributes['meta'], true));
                break;

            case 26:
                $result = __('logs.category_business_industry.edit', json_decode($this->attributes['meta'], true));
                break;
            case 27:
                $result = __('logs.category_business_type.edit', json_decode($this->attributes['meta'], true));
                break;
            case 28:
                $result = __('logs.category_business_type.create', json_decode($this->attributes['meta'], true));
                break;
            case 29:
                $result = __('logs.category_business_industry.create', json_decode($this->attributes['meta'], true));
                break;

            case 30:
                $result = __('logs.notes_clearance.create', json_decode($this->attributes['meta'], true));
                break;

            case 31:
                $result = __('logs.notes_clearance.delete', json_decode($this->attributes['meta'], true));
                break;
        }

        return $result;
    }
}
