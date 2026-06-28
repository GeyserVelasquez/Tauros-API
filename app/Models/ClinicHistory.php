<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use App\Observers\ClinicHistoryObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'description', 'attributes', 'livestock_id', 'technician_id'])]
#[Includable(['livestock', 'technician', 'clinicDiagnostics', 'clinicalTreatments'])]
#[Filterable(['code', 'name', 'livestock_id', 'technician_id'])]
#[Sortable(['id', 'code', 'name', 'created_at'])]
#[ObservedBy(ClinicHistoryObserver::class)]
class ClinicHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'attributes' => 'array',
        ];
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function clinicDiagnostics(): BelongsToMany
    {
        return $this->belongsToMany(ClinicDiagnostic::class, 'clinic_history_diagnostics');
    }

    public function clinicalTreatments(): BelongsToMany
    {
        return $this->belongsToMany(ClinicalTreatment::class, 'clinic_history_treatments');
    }

    public function aborts(): HasMany
    {
        return $this->hasMany(Abort::class, 'mother_history_id');
    }

    public function milkings(): HasMany
    {
        return $this->hasMany(Milking::class, 'mother_history_id');
    }
}
