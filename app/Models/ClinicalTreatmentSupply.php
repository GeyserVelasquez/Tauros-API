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

#[Fillable(['supply_id', 'quantity', 'clinical_treatment_id'])]
#[Includable(['supply', 'clinicalTreatment'])]
#[Filterable(['supply_id', 'clinical_treatment_id'])]
#[Sortable(['id', 'quantity', 'created_at'])]
class ClinicalTreatmentSupply extends Model
{
    use HasFactory, SoftDeletes;

    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class);
    }

    public function clinicalTreatment(): BelongsTo
    {
        return $this->belongsTo(ClinicalTreatment::class);
    }
}
