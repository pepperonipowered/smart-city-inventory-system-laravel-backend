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
        Schema::create('inventory_accesses', function (Blueprint $table) {
            $table->id();
            $table->boolean('for_dashboard')->default(false);
            $table->boolean('for_transactions')->default(false);
            $table->boolean('for_inventory')->default(false);
            $table->boolean('for_offices')->default(false);
            $table->boolean('for_categories')->default(false);
            $table->boolean('for_borrowers')->default(false);
            $table->boolean('for_users')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_accesses');
    }
};
