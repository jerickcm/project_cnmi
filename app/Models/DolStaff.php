<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DolStaff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "dol_staff";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'company_name',
        'contact_address',
        'contact_number',
        'business_id',
        'business_type_id',
        'verified',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
