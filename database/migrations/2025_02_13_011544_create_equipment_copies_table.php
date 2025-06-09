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
        Schema::create('equipment_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('office_equipments')->onDelete('cascade');
            $table->boolean('is_available')->default(true);
            $table->integer('copy_num');
            $table->string('serial_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_copies');
    }
};
