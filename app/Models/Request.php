<?php

namespace App\Models;

use App\Enums\RequestItemStatusEnum;
use App\Enums\RequestStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'status',
        'submitted_by',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'status' => RequestStatusEnum::class,
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RequestItem::class);
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', RequestStatusEnum::DRAFT);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', RequestStatusEnum::SUBMITTED);
    }

    public function scopeInReview($query)
    {
        return $query->where('status', RequestStatusEnum::IN_REVIEW);
    }

    // Helper methods
    public function canBeSubmitted(): bool
    {
        return $this->status === RequestStatusEnum::DRAFT && $this->items()->count() > 0;
    }

    public function canBeApproved(): bool
    {
        return $this->status === RequestStatusEnum::IN_REVIEW &&
               $this->items()->where('status', '!=', RequestItemStatusEnum::APPROVED)->count() === 0;
    }

    public function getTotalItemsCount(): int
    {
        return $this->items()->count();
    }

    public function getApprovedItemsCount(): int
    {
        return $this->items()->where('status', RequestItemStatusEnum::APPROVED)->count();
    }

    public function getRejectedItemsCount(): int
    {
        return $this->items()->where('status', RequestItemStatusEnum::REJECTED)->count();
    }
}
