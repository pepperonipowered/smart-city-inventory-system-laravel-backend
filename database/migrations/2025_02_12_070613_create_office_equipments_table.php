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
        Schema::create('office_equipments', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_name');
            $table->string('equipment_description');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->string('isc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_equipments');
    }
};
