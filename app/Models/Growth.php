<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['made_at', 'weight', 'height','growthable_id','growthable_type', 'growth_type_id', 'livestock_id'])]
class Growth extends Model
{
    use SoftDeletes, HasFactory;



    protected function casts(): array
    {
        return [
            'made_at' => 'date',
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
