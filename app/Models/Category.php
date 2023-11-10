<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{

    use HasFactory, SoftDeletes;
    protected $table = "categories";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'company_id',
        'business_id',
        'business_type_id',
    ];

    public function companylink()
    {
        return $this->belongsTo(Company::class, 'document_id', 'id');
    }

}
