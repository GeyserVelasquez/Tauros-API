<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use App\Enums\MovementType;
use App\Observers\MovementKardexObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['item_id', 'item_type', 'type', 'quantity', 'event_id', 'event_type', 'date'])]
#[Includable(['item', 'event'])]
#[Filterable(['item_type', 'item_id', 'type', 'event_type', 'event_id', 'date'])]
#[Sortable(['id', 'quantity', 'date', 'created_at'])]
#[ObservedBy(MovementKardexObserver::class)]
class MovementKardex extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'movement_kardex';

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'type' => MovementType::class,
            'quantity' => 'integer',
        ];
    }

    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    public function event(): MorphTo
    {
        return $this->morphTo();
    }
}
