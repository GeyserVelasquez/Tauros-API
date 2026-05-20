<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['supply_id', 'quantity', 'clinical_treatment_id'])]
class ClinicalTreatmentSupply extends Model
{
    use SoftDeletes, HasFactory;



    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class);
    }

    public function clinicalTreatment(): BelongsTo
    {
        return $this->belongsTo(ClinicalTreatment::class);
    }
}
