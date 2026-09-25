<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'result_count',
        'searched_at',
    ];

    protected function casts(): array
    {
        return [
            'result_count' => 'integer',
            'searched_at' => 'datetime',
        ];
    }
}
