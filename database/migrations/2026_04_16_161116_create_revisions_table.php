<?php

use App\Enums\RevisionResult;
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
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestock_id')->constrained('livestock');
            $table->date('made_at');
            $table->enum('revision_result', RevisionResult::cases());
            $table->foreignId('revision_type_id')->constrained('revision_types');
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
        Schema::dropIfExists('revisions');
    }
};
