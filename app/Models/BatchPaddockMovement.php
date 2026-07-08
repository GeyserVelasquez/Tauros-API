<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['batch_id', 'paddock_id', 'made_at'])]
#[Includable(['batch', 'paddock'])]
#[Filterable(['batch_id', 'paddock_id', 'made_at'])]
#[Sortable(['id', 'made_at', 'created_at'])]
class BatchPaddockMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'batch_paddock_movements';

    protected function casts(): array
    {
        return [
            'made_at' => 'datetime',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function paddock(): BelongsTo
    {
        return $this->belongsTo(Paddock::class);
    }
}
