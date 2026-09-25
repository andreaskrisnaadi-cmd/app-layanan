<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DownloadableForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'information_page_id',
        'name',
        'file_path',
        'version',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
        ];
    }

    public function informationPage(): BelongsTo
    {
        return $this->belongsTo(InformationPage::class);
    }
}
