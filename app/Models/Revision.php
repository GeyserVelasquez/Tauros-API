<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use App\Enums\RevisionResult;
use App\Observers\RevisionObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['livestock_id', 'made_at', 'revision_result', 'revision_type_id', 'technician_id'])]
#[Includable(['livestock', 'revisionType', 'technician'])]
#[Filterable(['livestock_id', 'revision_type_id', 'technician_id', 'made_at', 'revision_result', 'livestock.brand_number'])]
#[Sortable(['id', 'made_at', 'created_at'])]
#[ObservedBy(RevisionObserver::class)]
class Revision extends Model
{
    use HasFactory, SoftDeletes;

    public function event(): MorphOne
    {
        return $this->morphOne(Event::class, 'eventable');
    }

    protected function casts(): array
    {
        return [
            'made_at' => 'date',
            'revision_result' => RevisionResult::class,
        ];
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }

    public function revisionType(): BelongsTo
    {
        return $this->belongsTo(RevisionType::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }
}
