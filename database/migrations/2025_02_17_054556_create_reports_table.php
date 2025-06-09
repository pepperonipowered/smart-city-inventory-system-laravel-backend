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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->time('time_occurred'); #time occured
            $table->date('date_occurred'); #date occured
            $table->time('time_arrival_on_site'); #time arrived on site
            $table->string('name')->nullable();
            $table->string('landmark',255);
            $table->float('longitude',10,6);
            $table->float('latitude',10,6);
            $table->string('description',255)->nullable();
            $table->foreignId('source_id')->constrained('source')->onDelete('restrict');
            $table->foreignId('incident_id')->constrained('incident')->onDelete('restrict');
            $table->foreignId('barangay_id')->constrained('barangay')->onDelete('restrict');
            $table->foreignId('actions_id')->constrained('actions_taken')->onDelete('restrict'); #actions taken
            $table->foreignId('assistance_id')->constrained('type_of_assistance')->onDelete('restrict'); #type of assistance
            $table->foreignId('urgency_id')->constrained('urgency')->onDelete('restrict'); #urgency
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
