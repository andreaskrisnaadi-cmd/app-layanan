<?php

namespace App\Models;

use App\Enums\InformationCategory;
use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InformationPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'service_type_id',
        'description',
        'requirements',
        'procedure',
        'service_hours',
        'location',
        'contact',
        'publish_status',
        'published_at',
        'manager_id',
    ];

    protected function casts(): array
    {
        return [
            'category' => InformationCategory::class,
            'publish_status' => PublishStatus::class,
            'published_at' => 'datetime',
        ];
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function forms(): HasMany
    {
        return $this->hasMany(DownloadableForm::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class)->orderBy('sort_order');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(PageVisit::class);
    }
}
