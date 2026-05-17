<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\AnimalCategory;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('livestock', function (Blueprint $table) {
            $table->id();

            $table->string('brand_number');
            $table->string('electronic_code')->nullable();
            $table->string('name')->nullable();

            $table->date('entry_date')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('general_comment')->nullable();

            $table->unsignedTinyInteger('tits')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_alive')->default(true);

            $table->foreignId('entry_cause_id')->constrained();
            $table->foreignId('state_id')->constrained();
            $table->enum('animal_category', AnimalCategory::cases());
            $table->foreignId('breed_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('color_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('classification_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('father_id')->nullable()->constrained('livestock')->nullOnDelete();
            $table->foreignId('mother_id')->nullable()->constrained('livestock')->nullOnDelete();
            $table->foreignId('adoptive_mother_id')->nullable()->constrained('livestock')->nullOnDelete();
            $table->foreignId('receiving_mother_id')->nullable()->constrained('livestock')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock');
    }
};
