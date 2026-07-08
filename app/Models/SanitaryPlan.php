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

#[Fillable(['name', 'description'])]
#[Includable(['treatmentApplications'])]
#[Filterable(['name'])]
#[Sortable(['id', 'name', 'created_at'])]
class SanitaryPlan extends Model
{
    use HasFactory, SoftDeletes;

    public function treatmentApplications(): HasMany
    {
        return $this->hasMany(TreatmentApplication::class);
    }
}
