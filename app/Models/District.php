<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
