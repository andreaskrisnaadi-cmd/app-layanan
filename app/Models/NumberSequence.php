<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NumberSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix',
        'period',
        'last_number',
    ];

    protected function casts(): array
    {
        return [
            'last_number' => 'integer',
        ];
    }

    /**
     * Generate the next ticket/case/referral number with row locking to prevent race conditions.
     * Format: {prefix}-{period}-{padded_number}, e.g. DTSEN-202610-00012
     */
    public static function getNextNumber(string $prefix, ?string $period = null, int $padding = 5): string
    {
        $period = $period ?? now()->format('Ym');

        return DB::transaction(function () use ($prefix, $period, $padding) {
            $sequence = static::where('prefix', $prefix)
                ->where('period', $period)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                $sequence = static::create([
                    'prefix' => $prefix,
                    'period' => $period,
                    'last_number' => 0,
                ]);
            }

            $sequence->increment('last_number');

            return sprintf('%s-%s-%0' . $padding . 'd', $prefix, $period, $sequence->last_number);
        });
    }
}
