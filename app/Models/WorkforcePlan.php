<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkforcePlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "workforce_plans";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'document_id',
        'employer_id',
        'company_id',
        'full_name',
        'first_name',
        'middle_name',

        'last_name',
        'employment',
        'visa_expiration_date',
        'occupational_classification_code',
        'timetable_replacement_foreignworkers',
        'specific_replacement_plan',

    ];

    
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
