<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyDocumentFactory> */
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
