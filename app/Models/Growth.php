<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use App\Observers\GrowthObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['weight', 'height', 'made_at', 'livestock_id', 'growth_type_id', 'technician_id', 'growthable_id', 'growthable_type'])]
#[Includable(['livestock', 'growthType', 'technician', 'growthable'])]
#[Filterable(['livestock_id', 'growth_type_id', 'technician_id', 'made_at'])]
#[Sortable(['id', 'weight', 'height', 'made_at', 'created_at'])]
#[ObservedBy(GrowthObserver::class)]
class Growth extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'made_at' => 'date',
            'weight' => 'decimal:2',
            'height' => 'decimal:2',
        ];
    }

    public function growthable(): MorphTo
    {
        return $this->morphTo();
    }

    public function growthType(): BelongsTo
    {
        return $this->belongsTo(GrowthType::class);
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
