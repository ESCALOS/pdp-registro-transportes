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
        'status',
    ];

    protected $casts = [
        'status' => \App\Enums\ChassisStatusEnum::class,
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
