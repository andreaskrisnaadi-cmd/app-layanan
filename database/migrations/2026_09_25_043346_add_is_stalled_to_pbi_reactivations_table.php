<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pbi_reactivations', function (Blueprint $table) {
            $table->boolean('is_stalled')->default(false)->after('reactivated_date');
            $table->index('is_stalled');
        });
    }

    public function down(): void
    {
        Schema::table('pbi_reactivations', function (Blueprint $table) {
            $table->dropIndex(['is_stalled']);
            $table->dropColumn('is_stalled');
        });
    }
};
