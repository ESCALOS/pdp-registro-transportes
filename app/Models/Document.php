<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'type',
        'path',
        'submitted_date',
        'expiration_date',
        'status',
    ];

    protected $casts = [
        'type' => \App\Enums\DocumentTypeEnum::class,
        'submitted_date' => 'date',
        'expiration_date' => 'date',
    ];

    public function documentable()
    {
        return $this->morphTo();
    }
}
