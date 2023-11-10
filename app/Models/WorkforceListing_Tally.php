<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkforceListing_Tally extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "workforce_listing_tallies";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'company_id',
        'document_id',
        'employer_id',
        'file_id',
        'fulltime_us_workers',
        'parttime_us_workers',

        'fulltime_non_us_workers',
        'parttime_non_us_workers',
        'name_and_position',
        'year_and_quarter',
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
