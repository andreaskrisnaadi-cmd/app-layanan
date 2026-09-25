<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DtsenPurpose extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'max_decile',
        'validity_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'max_decile' => 'integer',
            'validity_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(DtsenCertificate::class);
    }
}
