<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'made_at', 'milking_type_id', 'first_weight', 'second_weight',
    'third_weight', 'livestock_id', 'technician_id'
])]
class Milking extends Model
{
    use SoftDeletes, HasFactory;



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
