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
        Schema::create('milkings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->constrained('livestock');
            $table->foreignId('technician_id')->nullable()->constrained()->nullOnDelete();
            $table->date('made_at');
            $table->foreignId('milking_type_id')->constrained();
            $table->decimal('first_weight');
            $table->decimal('second_weight');
            $table->decimal('third_weight');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milkings');
    }
};
