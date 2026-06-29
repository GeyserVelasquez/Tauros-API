<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use App\Observers\NewbornObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['birth_id', 'newborn_type_id', 'livestock_id'])]
#[Includable(['birth', 'newbornType', 'livestock'])]
#[Filterable(['birth_id', 'newborn_type_id', 'livestock_id'])]
#[Sortable(['id', 'created_at'])]
#[ObservedBy(NewbornObserver::class)]
class Newborn extends Model
{
    use HasFactory, SoftDeletes;

    public function birth(): BelongsTo
    {
        return $this->belongsTo(Birth::class);
    }

    public function newbornType(): BelongsTo
    {
        return $this->belongsTo(NewbornType::class);
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }
}
