<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\CompanyStatusEnum;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, SoftDeletes;

    use HasPanelShield;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'dni',
        'name',
        'last_name',
        'email',
        'password',
        'company_id',
        'is_company_representative',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function companyIsActive(): bool
    {
        return $this->company?->is_active;
    }

    public function companyStatus(): CompanyStatusEnum
    {
        return $this->company?->status;
    }
}
