<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['livestock', 'eventable_type', 'eventable_id', 'made_at'])]
#[Includable(['livestock', 'eventable'])]
#[Filterable(['livestock', 'eventable_type', 'eventable_id', 'made_at'])]
#[Sortable(['id', 'made_at', 'created_at'])]
class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'made_at' => 'date',
        ];
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }

    public function eventable(): MorphTo
    {
        return $this->morphTo();
    }
}
