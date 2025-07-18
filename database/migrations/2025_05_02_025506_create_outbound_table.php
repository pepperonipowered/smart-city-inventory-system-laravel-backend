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
        Schema::create('outbound', function (Blueprint $table) {
            $table->id();
            $table->foreignId('road_id')->constrained('roads')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('status_traffic')->onDelete('cascade');
            $table->json('coordinates')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound');
    }
};
