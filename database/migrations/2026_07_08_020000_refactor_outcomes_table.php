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
        // 1. Create death_causes table
        Schema::create('death_causes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });

        // 2. Modify outcomes table
        Schema::table('outcomes', function (Blueprint $table) {
            // Drop foreign key and column for outcome_type_id
            $table->dropForeign(['outcome_type_id']);
            $table->dropColumn('outcome_type_id');

            // Add new enum outcome_type column
            $table->enum('outcome_type', ['death', 'sale', 'transfer', 'slaughter']);

            // Add nullable death_cause_id foreign key
            $table->foreignId('death_cause_id')->nullable()->constrained('death_causes');
        });

        // 3. Drop outcome_types table
        Schema::dropIfExists('outcome_types');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Re-create outcome_types table
        Schema::create('outcome_types', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('outcomes', function (Blueprint $table) {
            // Drop death_cause_id and outcome_type
            $table->dropForeign(['death_cause_id']);
            $table->dropColumn(['death_cause_id', 'outcome_type']);

            // Add back outcome_type_id
            $table->foreignId('outcome_type_id')->nullable()->constrained('outcome_types');
        });

        // 2. Drop death_causes table
        Schema::dropIfExists('death_causes');
    }
};
