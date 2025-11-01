<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'document_type',
        'document_number',
        'name',
        'lastname',
        'status',
    ];

    protected $casts = [
        'document_type' => \App\Enums\DriverDocumentTypeEnum::class,
        'status' => \App\Enums\DriverStatusEnum::class,
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
