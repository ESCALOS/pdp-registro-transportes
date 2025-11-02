<?php

namespace App\Models;

use App\Enums\RequestItemStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_item_id',
        'type',
        'path',
        'submitted_date',
        'expiration_date',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'status' => RequestItemStatusEnum::class,
        'submitted_date' => 'date',
        'expiration_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function requestItem(): BelongsTo
    {
        return $this->belongsTo(RequestItem::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
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

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiration_date', '<=', now()->addDays($days));
    }

    // Helper methods
    public function isExpired(): bool
    {
        return $this->expiration_date < now();
    }

    public function isExpiringSoon($days = 30): bool
    {
        return $this->expiration_date <= now()->addDays($days);
    }

    public function getDaysUntilExpiration(): int
    {
        return (int) now()->diffInDays($this->expiration_date, false);
    }
}
