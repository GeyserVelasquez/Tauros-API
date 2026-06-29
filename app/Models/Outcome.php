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

#[Fillable(['made_at', 'outcome_type_id', 'livestock_id'])]
#[Includable(['outcomeType', 'livestock'])]
#[Filterable(['outcome_type_id', 'livestock_id', 'made_at'])]
#[Sortable(['id', 'made_at', 'created_at'])]
class Outcome extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'made_at' => 'date',
        ];
    }

    public function outcomeType(): BelongsTo
    {
        return $this->belongsTo(OutcomeType::class);
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }
}
