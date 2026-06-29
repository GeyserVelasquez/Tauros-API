<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'herd_id'])]
#[Includable(['herd', 'livestock', 'batchMovements', 'extractions'])]
#[Filterable(['code', 'name', 'herd_id'])]
#[Sortable(['id', 'code', 'name', 'created_at'])]
class Batch extends Model
{
    use HasFactory, SoftDeletes;

    public function herd(): BelongsTo
    {
        return $this->belongsTo(Herd::class);
    }

    public function livestock(): BelongsToMany
    {
        return $this->belongsToMany(Livestock::class);
    }

    public function batchMovements(): BelongsTo
    {
        return $this->belongsTo(BatchMovement::class);
    }

    public function batchMovement(): HasMany
    {
        return $this->hasMany(BatchMovement::class);
    }

    public function extractions(): MorphMany
    {
        return $this->morphMany(Extraction::class, 'batch');
    }
}
