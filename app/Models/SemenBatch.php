<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable(['code', 'name', 'description', 'livestock_id', 'technician_id'])]
class SemenBatch extends Model
{
    use SoftDeletes, HasFactory;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
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

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'origin');
    }

    public function services(): MorphMany
    {
        return $this->morphMany(Service::class, 'parental');
    }

    public function extractions(): MorphMany
    {
        return $this->morphMany(Extraction::class, 'batch');
    }
}
