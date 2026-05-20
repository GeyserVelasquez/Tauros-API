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
        Schema::create('growths', function (Blueprint $table) {
            $table->id();
            $table->date('made_at');
            $table->decimal('weight');
            $table->decimal('height');
            $table->morphs('growthable');
            $table->foreignId('growth_type_id')->constrained();
            $table->foreignId('livestock_id')->constrained('livestock');
            $table->foreignId('technician_id')->nullable()->constrained()->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growths');
    }
};
