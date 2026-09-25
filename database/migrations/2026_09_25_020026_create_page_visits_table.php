<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('information_page_id')->constrained('information_pages')->cascadeOnDelete();
            $table->date('visit_date');
            $table->integer('visit_count')->default(1);
            $table->timestamps();

            $table->unique(['information_page_id', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
