<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('service_type_id')->constrained('service_types')->restrictOnDelete();
            $table->foreignId('submitter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('applicant_name');
            $table->char('applicant_nik', 16);
            $table->char('family_card_number', 16);
            $table->text('address');
            $table->foreignId('village_id')->constrained('villages')->restrictOnDelete();
            $table->string('phone');
            $table->timestampTz('submitted_at')->nullable();
            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('work_unit_id')->nullable()->constrained('work_units')->nullOnDelete();
            $table->string('status')->default('submitted')->index();
            $table->boolean('is_priority')->default(false);
            $table->text('verification_result')->nullable();
            $table->text('officer_notes')->nullable();
            $table->text('assessment_notes')->nullable();
            $table->text('service_result')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestampTz('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'village_id']);
            $table->index(['service_type_id', 'status']);
            $table->index('applicant_nik');
            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
