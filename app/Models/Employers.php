<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employers extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "employers";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'company_id',
        'company_name',
        'contact_address',
        'contact_number',
        'verified',
       
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
