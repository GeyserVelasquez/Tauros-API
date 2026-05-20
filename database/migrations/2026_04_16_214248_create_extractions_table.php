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
        Schema::create('extractions', function (Blueprint $table) {
            $table->id();
            $table->morphs('batch');
            $table->date('made_at');
            $table->foreignId('technician_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('extraction_type_id')->constrained('extraction_types')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extractions');
    }
};
