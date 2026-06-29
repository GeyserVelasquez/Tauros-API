<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use App\Enums\MovementType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['supply_id', 'type', 'made_at', 'attributes'])]
#[Includable(['supply'])]
#[Filterable(['supply_id', 'type', 'made_at'])]
#[Sortable(['id', 'made_at', 'created_at'])]
class SupplyMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'made_at' => 'datetime',
            'attributes' => 'array',
            'type' => MovementType::class,
        ];
    }

    public function supply(): BelongsTo
    {
        return $this->belongsTo(supply::class);
    }
}
