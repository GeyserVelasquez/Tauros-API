<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'code', 'area'])]
#[Includable(['batches', 'livestock', 'paddockMovements', 'batchPaddockMovements'])]
#[Filterable(['name', 'code'])]
#[Sortable(['id', 'name', 'code', 'area', 'created_at'])]
class Paddock extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'area' => 'decimal:2',
        ];
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function livestock(): HasMany
    {
        return $this->hasMany(Livestock::class);
    }

    public function paddockMovements(): HasMany
    {
        return $this->hasMany(PaddockMovement::class);
    }

    public function batchPaddockMovements(): HasMany
    {
        return $this->hasMany(BatchPaddockMovement::class);
    }
}
