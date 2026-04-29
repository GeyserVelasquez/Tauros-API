<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['made_at', 'outcome_type_id', 'livestock_id'])]
class Outcome extends Model
{
    use SoftDeletes, HasFactory;

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
