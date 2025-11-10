<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    /** @use HasFactory<\Database\Factories\TruckFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'license_plate',
        'nationality',
        'is_internal',
        'truck_type',
        'has_bonus',
        'tare',
        'status',
        'appeal_token',
        'appeal_token_expires_at',
    ];

    protected $casts = [
        'status' => \App\Enums\TruckStatusEnum::class,
        'is_internal' => 'boolean',
        'has_bonus' => 'boolean',
        'tare' => 'decimal:2',
        'appeal_token_expires_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function requestItems()
    {
        return $this->morphMany(RequestItem::class, 'itemable');
    }
}
