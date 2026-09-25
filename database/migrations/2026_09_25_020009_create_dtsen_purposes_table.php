<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dtsen_purposes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // spmb, pip, kip_kuliah, bansos, kesehatan, lainnya
            $table->string('name');
            $table->unsignedTinyInteger('max_decile');
            $table->integer('validity_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dtsen_purposes');
    }
};
