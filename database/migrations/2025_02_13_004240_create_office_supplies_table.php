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
        Schema::create('office_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('supply_name');
            $table->string('supply_description');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->integer('supply_quantity');
            $table->string('image_path')->nullable();
            $table->string('isc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_supplies');
    }
};
