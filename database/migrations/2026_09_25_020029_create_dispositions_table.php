<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();
            $table->string('dispositionable_type');
            $table->unsignedBigInteger('dispositionable_id');
            $table->foreignId('from_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('to_work_unit_id')->constrained('work_units')->restrictOnDelete();
            $table->foreignId('to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('instructions');
            $table->timestampTz('disposed_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['dispositionable_type', 'dispositionable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispositions');
    }
};
