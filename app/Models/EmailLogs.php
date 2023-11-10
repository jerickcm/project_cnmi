<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class EmailLogs extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "email_logs";

    const TYPE_SEND_EMAIL = 0;
    const TYPE_CREATE_USER = 0;

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
        return Carbon::parse($this->requestdate)->dayName . " " . Carbon::parse($this->requestdate)->isoFormat(', MMM Do YYYY ');
    }

    public function getUserEmailAttribute()
    {
        return  User::select('email')->where('id', $this->user_id)->first()['email'];
    }

    public function getUserNameAttribute()
    {
        return  User::select('full_name')->where('id', $this->user_id)->first()['full_name'];
    }

    public function getMetaAttribute($value)
    {
        return json_decode($value);
    }

    public function setMetaAttribute($value)
    {
        $this->attributes['meta'] = json_encode($value);
    }

    public function getDescriptionAttribute()
    {
        switch ($this->attributes['type']) {
            case 0:
                $result = __('mail_logs.user.send', json_decode($this->attributes['meta'], true));
                break;
        }

        return $result;
    }
}
