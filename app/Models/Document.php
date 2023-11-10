<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "documents";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'company_id',
        'employer_id',
        'type',
        'orig_title',
        'business_industry_id',
        'business_type_id',
        'title',
        'file',
        'notes',
        'year',
        'quarter',
        'approved',
        'registration_date',
    ];


    public function wfplan()
    {
        return $this->hasMany(WorkforcePlan::class, 'document_id', 'id');
    }

    public function wflist()
    {
        return $this->hasMany(WorkforceListing::class, 'document_id', 'id');
    }

    public function wfplantally()
    {
        return $this->hasMany(WorkforcePlan_certification::class, 'document_id', 'id');
    }

    public function wflisttally()
    {
        return $this->hasMany(WorkforceListing_Tally::class, 'document_id', 'id');
    }
}
