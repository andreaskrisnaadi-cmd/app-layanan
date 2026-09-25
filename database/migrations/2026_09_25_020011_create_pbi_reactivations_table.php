<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pbi_reactivations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->unique()->constrained('service_requests')->cascadeOnDelete();
            $table->string('participant_name');
            $table->char('participant_nik', 16);
            $table->string('bpjs_card_number');
            $table->date('deactivated_date')->nullable();
            $table->string('reason'); // chronic|catastrophic|emergency|newborn|other
            $table->string('health_facility_name')->nullable();
            $table->string('health_letter_number')->nullable();
            $table->unsignedTinyInteger('decile')->nullable();
            $table->text('eligibility_notes')->nullable();
            $table->string('recommendation_number')->nullable()->unique();
            $table->timestampTz('recommendation_issued_at')->nullable();
            $table->foreignId('signer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampTz('proposed_to_ministry_at')->nullable();
            $table->string('ministry_decision')->default('pending'); // pending|approved|rejected
            $table->timestampTz('ministry_decided_at')->nullable();
            $table->date('reactivated_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('reason');
            $table->index('ministry_decision');
            $table->index('proposed_to_ministry_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pbi_reactivations');
    }
};
