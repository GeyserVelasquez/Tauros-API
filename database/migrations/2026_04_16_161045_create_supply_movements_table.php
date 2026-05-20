<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supply_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_id')->constrained();
            $table->enum('type', ['income','outcome','loss']);
            $table->dateTime('made_at')->useCurrent();
            $table->json('attributes')->nullable();
            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_movements');
    }
};
