<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Company extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "companies";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'company_name',
        'contact_number',
        'contact_address',
        'status',
        'verified',
        'registration_date',
        'create_date'
    ];
    protected $appends = ['createddate'];

    public function getcreateddateAttribute()
    {
        return Carbon::parse($this->created_at)->dayName . " " . Carbon::parse($this->created_at)->isoFormat(', MMM Do YYYY hh:mm A');
    }

    public function categorylink()
    {
        return $this->hasMany(Category::class, 'company_id', 'id');
    }
}
