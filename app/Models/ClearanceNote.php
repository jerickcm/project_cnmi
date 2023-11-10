<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ClearanceNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "clearance_notes";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'employer_id',
        'company_id',
        'note',
    ];

    protected $appends = ['createddate'];

    public function getcreateddateAttribute()
    {
        return Carbon::parse($this->created_at)->dayName . " " . Carbon::parse($this->created_at)->isoFormat(', MMM Do YYYY ');
    }
}
