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

#[Fillable(['livestock_id', 'paddock_id', 'made_at'])]
#[Includable(['livestock', 'paddock'])]
#[Filterable(['livestock_id', 'paddock_id', 'made_at'])]
#[Sortable(['id', 'made_at', 'created_at'])]
class PaddockMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'paddock_movements';

    protected function casts(): array
    {
        return [
            'made_at' => 'datetime',
        ];
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }

    public function paddock(): BelongsTo
    {
        return $this->belongsTo(Paddock::class);
    }
}
