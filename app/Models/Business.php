<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Business extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "businesses";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'industry',
        'notes',
    ];
    protected $appends = ['createddate'];
    public function b_type()
    {
        return $this->hasMany(BusinessType::class, 'business_id', 'id');
    }

    public function getcreateddateAttribute()
    {
        return Carbon::parse($this->created_at)->dayName . " " . Carbon::parse($this->created_at)->isoFormat(', MMM Do YYYY hh:mm A');
    }
}
