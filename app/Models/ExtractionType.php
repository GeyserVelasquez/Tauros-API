<?php

namespace App\Models;

use App\Attributes\Includable;
use App\Traits\HasInclude;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name'])]
#[Includable(['extractions'])]
class ExtractionType extends Model
{
    use SoftDeletes, HasFactory, HasInclude;

    public function extractions(): HasMany
    {
        return $this->hasMany(Extraction::class);
    }
}
