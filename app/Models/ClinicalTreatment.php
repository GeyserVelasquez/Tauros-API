<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'attributes'])]
#[Includable(['clinicHistories', 'clinicalTreatmentSupplies'])]
#[Filterable(['code', 'name'])]
#[Sortable(['id', 'code', 'name', 'created_at'])]
class ClinicalTreatment extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'attributes' => 'array',
        ];
    }

    public function clinicHistories(): BelongsToMany
    {
        return $this->belongsToMany(ClinicHistory::class, 'clinic_history_treatments');
    }

    public function clinicalTreatmentSupplies(): HasMany
    {
        return $this->hasMany(ClinicalTreatmentSupply::class);
    }
}
