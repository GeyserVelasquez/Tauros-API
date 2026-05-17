<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\MovementType;

#[Fillable(['supply_id', 'type', 'made_at', 'attributes'])]
class SupplyMovement extends Model
{
    use SoftDeletes, HasFactory;

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
