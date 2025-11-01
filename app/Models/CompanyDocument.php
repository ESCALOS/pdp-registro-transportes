<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyDocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'type',
        'path',
        'status',
        'rejection_reason',
        'submitted_date',
        'validated_by',
    ];

    protected $casts = [
        'type' => \App\Enums\CompanyDocumentTypeEnum::class,
        'status' => \App\Enums\CompanyDocumentStatusEnum::class,
        'submitted_date' => 'date',
        'validated_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
