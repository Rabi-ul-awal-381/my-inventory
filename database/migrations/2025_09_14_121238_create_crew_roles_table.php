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
        Schema::create('crew_roles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('crew_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Photographer", "Stylist", "Manager"
            $table->string('color')->default('blue'); // For UI display
            $table->json('permissions'); // Custom permissions object
            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['crew_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crew_roles');
    }
};
