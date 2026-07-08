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

#[Fillable(['name'])]
#[Includable(['outcomes'])]
#[Filterable(['name'])]
#[Sortable(['id', 'name', 'created_at'])]
class DeathCause extends Model
{
    use HasFactory, SoftDeletes;

    public function outcomes(): HasMany
    {
        return $this->hasMany(Outcome::class);
    }
}
