<?php

namespace App\Models;

use App\Attributes\Includable;
use App\Enums\MovementType;
use App\Observers\MovementKardexObserver;
use App\Traits\HasInclude;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['item_id', 'item_type', 'type', 'quantity', 'event_id', 'event_type', 'date'])]
#[Includable(['item', 'event'])]
#[ObservedBy(MovementKardexObserver::class)]
class MovementKardex extends Model
{
    use SoftDeletes, HasFactory, HasInclude;

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
