<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('password');
            $table->char('nik', 16)->nullable()->after('phone');
            $table->foreignId('work_unit_id')->nullable()->after('nik')->constrained('work_units')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('work_unit_id')->constrained('districts')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->after('district_id')->constrained('villages')->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('village_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['work_unit_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn([
                'phone',
                'nik',
                'work_unit_id',
                'district_id',
                'village_id',
                'is_active',
            ]);
        });
    }
};
