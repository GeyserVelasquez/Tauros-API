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

#[Fillable(['code', 'name'])]
#[Includable(['extractions'])]
#[Filterable(['code', 'name'])]
#[Sortable(['id', 'code', 'name', 'created_at'])]
class ExtractionType extends Model
{
    use HasFactory, SoftDeletes;

    public function extractions(): HasMany
    {
        return $this->hasMany(Extraction::class);
    }
}
