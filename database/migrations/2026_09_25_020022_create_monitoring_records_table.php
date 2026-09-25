<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rehabilitation_case_id')->constrained('rehabilitation_cases')->cascadeOnDelete();
            $table->foreignId('referral_id')->nullable()->constrained('referrals')->nullOnDelete();
            $table->foreignId('officer_id')->constrained('users')->restrictOnDelete();
            $table->date('monitoring_date');
            $table->text('progress');
            $table->text('result_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('monitoring_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_records');
    }
};
