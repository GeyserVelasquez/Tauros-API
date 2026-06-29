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

#[Fillable([
    'made_at', 'milking_type_id', 'first_weight', 'second_weight',
    'third_weight', 'livestock_id', 'technician_id',
])]
#[Includable(['milkingType', 'livestock', 'technician'])]
#[Filterable(['milking_type_id', 'livestock_id', 'technician_id', 'made_at'])]
#[Sortable(['id', 'made_at', 'first_weight', 'second_weight', 'third_weight', 'created_at'])]
class Milking extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'made_at' => 'date',
        ];
    }

    public function milkingType(): BelongsTo
    {
        return $this->belongsTo(MilkingType::class);
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }
}
