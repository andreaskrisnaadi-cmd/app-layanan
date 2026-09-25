<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DtsenCertificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_request_id',
        'dtsen_purpose_id',
        'purpose_description',
        'subject_name',
        'subject_nik',
        'relationship_to_applicant',
        'is_registered',
        'decile',
        'checked_at',
        'checker_id',
        'certificate_number',
        'issued_at',
        'valid_until',
        'signer_id',
        'file_path',
        'verification_code',
    ];

    protected function casts(): array
    {
        return [
            'is_registered' => 'boolean',
            'decile' => 'integer',
            'checked_at' => 'datetime',
            'issued_at' => 'datetime',
            'valid_until' => 'date',
        ];
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function purpose(): BelongsTo
    {
        return $this->belongsTo(DtsenPurpose::class, 'dtsen_purpose_id');
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checker_id');
    }

    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signer_id');
    }

    public function approvals(): MorphMany
    {
        return $this->morphMany(Approval::class, 'approvable');
    }
}
