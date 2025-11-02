<?php

namespace App\Models;

use App\Enums\RequestItemStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'itemable_type',
        'itemable_id',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'status' => RequestItemStatusEnum::class,
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RequestDocument::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', RequestItemStatusEnum::PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', RequestItemStatusEnum::APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', RequestItemStatusEnum::REJECTED);
    }

    // Helper methods
    public function canBeApproved(): bool
    {
        return $this->status === RequestItemStatusEnum::PENDING &&
               $this->documents()->where('status', '!=', RequestItemStatusEnum::APPROVED)->count() === 0;
    }

    public function getTotalDocumentsCount(): int
    {
        return $this->documents()->count();
    }

    public function getApprovedDocumentsCount(): int
    {
        return $this->documents()->where('status', RequestItemStatusEnum::APPROVED)->count();
    }

    public function getRejectedDocumentsCount(): int
    {
        return $this->documents()->where('status', RequestItemStatusEnum::REJECTED)->count();
    }

    public function getItemTypeName(): string
    {
        return match ($this->itemable_type) {
            'App\Models\Driver' => 'Conductor',
            'App\Models\Truck' => 'Camión',
            'App\Models\Chassis' => 'Chasis',
            default => 'Desconocido',
        };
    }
}
