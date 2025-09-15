<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('crew_users', function (Blueprint $table) {
            $table->foreignId('crew_role_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crew_users', function (Blueprint $table) {
            $table->dropForeign(['crew_role_id']);
            $table->dropColumn('crew_role_id');
        });
    }
};
