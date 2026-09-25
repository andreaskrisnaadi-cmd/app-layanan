<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonitoringRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rehabilitation_case_id',
        'referral_id',
        'officer_id',
        'monitoring_date',
        'progress',
        'result_notes',
    ];

    protected function casts(): array
    {
        return [
            'monitoring_date' => 'date',
        ];
    }

    public function rehabilitationCase(): BelongsTo
    {
        return $this->belongsTo(RehabilitationCase::class);
    }

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
