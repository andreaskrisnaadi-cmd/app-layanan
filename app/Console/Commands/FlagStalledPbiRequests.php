<?php

namespace App\Console\Commands;

use App\Enums\ServiceRequestStatus;
use App\Models\PbiReactivation;
use Illuminate\Console\Command;

class FlagStalledPbiRequests extends Command
{
    protected $signature = 'sapa:flag-stalled-pbi';

    protected $description = 'Tandai usulan reaktivasi PBI-JK yang tertahan di Kemensos melebihi batas waktu';

    public function handle(): int
    {
        $stalledDays = (int) config('sapa.pbi_stalled_days', 30);
        $threshold = now()->subDays($stalledDays);

        $count = PbiReactivation::where('is_stalled', false)
            ->whereHas('serviceRequest', function ($q) use ($threshold) {
                $q->where('status', ServiceRequestStatus::ProposedToMinistry)
                    ->where('updated_at', '<=', $threshold);
            })
            ->update(['is_stalled' => true]);

        $this->info("Berhasil menandai {$count} pengajuan reaktivasi PBI-JK yang tertahan lebih dari {$stalledDays} hari.");

        return self::SUCCESS;
    }
}
