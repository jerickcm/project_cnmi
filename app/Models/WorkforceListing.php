<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkforceListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "workforce_listings";
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
        'major_soc_code',
        'minor_soc_code',
        'position',
        'project_exemption',
        'employment_status',
        'wage',
        'country_of_citizenship',
        'visa_type_class',
        'employment_start_date',
        'employment_end_date',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
