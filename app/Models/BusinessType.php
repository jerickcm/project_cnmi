<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class BusinessType extends Model
{

    use HasFactory, SoftDeletes;
    protected $table = "business_types";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'business_id',
        'type',
        'notes',
    ];
    protected $appends = ['createddate'];
    
    public function b_industry()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function getcreateddateAttribute()
    {
        return Carbon::parse($this->created_at)->dayName . " " . Carbon::parse($this->created_at)->isoFormat(', MMM Do YYYY hh:mm A');
    }
}
