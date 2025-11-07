<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chassis extends Model
{
    /** @use HasFactory<\Database\Factories\ChassisFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'license_plate',
        'vehicle_type',
        'axle_count',
        'has_bonus',
        'tare',
        'safe_weight',
        'height',
        'length',
        'width',
        'is_insulated',
        'material',
        'accepts_20ft',
        'accepts_40ft',
        'status',
        'appeal_token',
        'appeal_token_expires_at',
    ];

    protected $casts = [
        'status' => \App\Enums\ChassisStatusEnum::class,
        'has_bonus' => 'boolean',
        'tare' => 'decimal:2',
        'safe_weight' => 'decimal:2',
        'height' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'is_insulated' => 'boolean',
        'accepts_20ft' => 'boolean',
        'accepts_40ft' => 'boolean',
        'axle_count' => 'integer',
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
