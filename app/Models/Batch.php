<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'paddock_id'])]
#[Includable(['paddock', 'livestock', 'batchMovements', 'batchPaddockMovements'])]
#[Filterable(['code', 'name', 'paddock_id'])]
#[Sortable(['id', 'code', 'name', 'created_at'])]
class Batch extends Model
{
    use HasFactory, SoftDeletes;

    public function paddock(): BelongsTo
    {
        return $this->belongsTo(Paddock::class);
    }

    public function livestock(): HasMany
    {
        return $this->hasMany(Livestock::class);
    }

    public function batchPaddockMovements(): HasMany
    {
        return $this->hasMany(BatchPaddockMovement::class);
    }

    public function batchMovements(): HasMany
    {
        return $this->hasMany(BatchMovement::class);
    }
}
