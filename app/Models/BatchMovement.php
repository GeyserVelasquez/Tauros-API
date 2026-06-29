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

#[Fillable(['batch_id', 'made_at', 'attributes', 'livestock_id'])]
#[Includable(['batch', 'livestock'])]
#[Filterable(['batch_id', 'livestock_id', 'made_at'])]
#[Sortable(['id', 'made_at', 'created_at'])]
class BatchMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'batch_movements';

    protected function casts(): array
    {
        return [
            'made_at' => 'date',
            'attributes' => 'json',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }
}
