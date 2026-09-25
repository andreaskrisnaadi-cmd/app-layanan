<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'information_page_id',
        'visit_date',
        'visit_count',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'visit_count' => 'integer',
        ];
    }

    public function informationPage(): BelongsTo
    {
        return $this->belongsTo(InformationPage::class);
    }
}
