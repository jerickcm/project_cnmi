<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WorkforcePlan_certification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "workforce_plan_certifications";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'company_id',
        'document_id',
        'employer_id',
        'file_id',
        'name_and_position',
        'company_name',
        'dba',
        'day',
        'month',
        'year',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
