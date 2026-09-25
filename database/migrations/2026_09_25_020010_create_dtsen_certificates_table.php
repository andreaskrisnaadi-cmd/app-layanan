<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dtsen_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->unique()->constrained('service_requests')->cascadeOnDelete();
            $table->foreignId('dtsen_purpose_id')->constrained('dtsen_purposes')->restrictOnDelete();
            $table->text('purpose_description')->nullable();
            $table->string('subject_name');
            $table->char('subject_nik', 16);
            $table->string('relationship_to_applicant');
            $table->boolean('is_registered')->default(false);
            $table->unsignedTinyInteger('decile')->nullable();
            $table->timestampTz('checked_at')->nullable();
            $table->foreignId('checker_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('certificate_number')->nullable()->unique();
            $table->timestampTz('issued_at')->nullable();
            $table->date('valid_until')->nullable();
            $table->foreignId('signer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('file_path')->nullable();
            $table->string('verification_code')->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->index('decile');
            $table->index('issued_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dtsen_certificates');
    }
};
