<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'description', 'mother_id', 'father_id', 'technician_id'])]
#[Includable(['mother', 'father', 'technician', 'products', 'services', 'extractions'])]
#[Filterable(['code', 'name', 'mother_id', 'father_id', 'technician_id'])]
#[Sortable(['id', 'code', 'name', 'created_at'])]
class EmbrionBatch extends Model
{
    use HasFactory, SoftDeletes;

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Livestock::class, 'mother_id');
    }

    public function father(): BelongsTo
    {
        return $this->belongsTo(Livestock::class, 'father_id');
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
