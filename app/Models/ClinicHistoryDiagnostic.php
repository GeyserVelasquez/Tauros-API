<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['clinic_history_id', 'clinic_diagnostic_id'])]
#[Includable(['clinicHistory', 'clinicDiagnostic'])]
#[Filterable(['clinic_history_id', 'clinic_diagnostic_id'])]
#[Sortable(['id', 'created_at'])]
class ClinicHistoryDiagnostic extends Model
{
    use SoftDeletes;

    public function clinicHistory(): BelongsTo
    {
        return $this->belongsTo(ClinicHistory::class);
    }

    public function clinicDiagnostic(): BelongsTo
    {
        return $this->belongsTo(ClinicDiagnostic::class);
    }
}
