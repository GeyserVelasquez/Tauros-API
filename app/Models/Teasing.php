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

#[Fillable(['detected_at', 'livestock_id', 'technician_id'])]
#[Includable(['livestock', 'technician'])]
#[Filterable(['livestock_id', 'technician_id', 'detected_at'])]
#[Sortable(['id', 'detected_at', 'created_at'])]
class Teasing extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'detected_at' => 'date',
        ];
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
