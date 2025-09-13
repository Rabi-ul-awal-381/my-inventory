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
        Schema::create('crew_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crew_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('viewer'); // owner, editor, uploader, viewer
            $table->json('custom_permissions')->nullable();
            $table->timestamps();

            $table->unique(['crew_id', 'user_id']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crew_users');
    }
};
