<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'ruc',
        'business_name',
        'status',
    ];

    protected $casts = [
        'type' => \App\Enums\CompanyTypeEnum::class,
        'status' => \App\Enums\CompanyStatusEnum::class,
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function trucks()
    {
        return $this->hasMany(Truck::class);
    }

    public function chassis()
    {
        return $this->hasMany(Chassis::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
