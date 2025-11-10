<?php

namespace App\Models;

use App\Enums\{CompanyTypeEnum, CompanyStatusEnum};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'ruc',
        'business_name',
        'status',
        'is_active',
    ];

    protected $casts = [
        'type' => CompanyTypeEnum::class,
        'status' => CompanyStatusEnum::class,
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function representative()
    {
        return $this->hasOne(User::class)->where('is_company_representative', true);
    }

    public function documents()
    {
        return $this->hasMany(CompanyDocument::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, CompanyTypeEnum $type)
    {
        return $query->where('type', $type);
    }

    public function scopePending($query)
    {
        return $query->where('status', CompanyStatusEnum::PENDIENTE);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', CompanyStatusEnum::APROBADO);
    }
}
