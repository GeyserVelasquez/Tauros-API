<?php

namespace App\Models;

use App\Attributes\Includable;
use App\Observers\ExtractionObserver;
use App\Traits\HasInclude;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['batch_type', 'batch_id', 'technician_id', 'extraction_type_id', 'made_at'])]
#[Includable(['batch', 'technician', 'extractionType'])]
#[ObservedBy(ExtractionObserver::class)]
class Extraction extends Model
{
    use SoftDeletes, HasFactory;

    protected function casts(): array
    {
        return [
            'made_at' => 'date',
        ];
    }

    public function batch(): MorphTo
    {
        return $this->morphTo();
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function extractionType(): BelongsTo
    {
        return $this->belongsTo(ExtractionType::class);
    }
}
