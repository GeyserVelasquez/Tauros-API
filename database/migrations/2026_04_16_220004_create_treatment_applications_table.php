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
        Schema::create('treatment_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->constrained('livestock')->cascadeOnDelete();
            $table->foreignId('clinical_treatment_id')->constrained('clinical_treatments');
            $table->foreignId('supply_id')->nullable()->constrained('supplies');
            $table->integer('dose_number')->nullable();
            $table->integer('quantity'); // Dosis * 100
            
            $table->dateTime('scheduled_date');
            $table->dateTime('applied_at')->nullable();
            $table->foreignId('applied_by_id')->nullable()->constrained('technicians')->nullOnDelete();

            $table->foreignId('clinic_history_id')->nullable()->constrained('clinic_histories')->nullOnDelete();
            $table->foreignId('sanitary_plan_id')->nullable()->constrained('sanitary_plans')->nullOnDelete();
            
            $table->softDeletes();
            $table->timestamps();
        });

        // Drop the old static pivot table clinical_treatment_supplies
        Schema::dropIfExists('clinical_treatment_supplies');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_applications');

        // Recreate the old static pivot table if rolled back
        Schema::create('clinical_treatment_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_id')->constrained();
            $table->decimal('quantity');
            $table->foreignId('clinical_treatment_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
