<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['clinic_history_id', 'clinical_treatment_id'])]
#[Includable(['clinicHistory', 'clinicalTreatment'])]
#[Filterable(['clinic_history_id', 'clinical_treatment_id'])]
#[Sortable(['id', 'created_at'])]
class ClinicHistoryTreatment extends Model
{
    use SoftDeletes;

    public function clinicHistory(): BelongsTo
    {
        return $this->belongsTo(ClinicHistory::class);
    }

    public function clinicalTreatment(): BelongsTo
    {
        return $this->belongsTo(ClinicalTreatment::class);
    }
}
