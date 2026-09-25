<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('information_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category'); // program|rehabilitation|disability|elderly|complaint|other
            $table->foreignId('service_type_id')->nullable()->constrained('service_types')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->text('procedure')->nullable();
            $table->string('service_hours')->nullable();
            $table->string('location')->nullable();
            $table->string('contact')->nullable();
            $table->string('publish_status')->default('draft')->index(); // draft|published|archived
            $table->timestampTz('published_at')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('category');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('information_pages');
    }
};
