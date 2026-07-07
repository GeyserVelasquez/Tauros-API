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

#[Fillable([
    'livestock_id',
    'clinical_treatment_id',
    'supply_id',
    'dose_number',
    'quantity',
    'scheduled_date',
    'applied_at',
    'applied_by_id',
    'clinic_history_id',
    'sanitary_plan_id'
])]
#[Includable(['livestock', 'clinicalTreatment', 'supply', 'appliedBy', 'clinicHistory', 'sanitaryPlan'])]
#[Filterable(['livestock_id', 'clinical_treatment_id', 'supply_id', 'applied_at', 'clinic_history_id', 'sanitary_plan_id','scheduled_date'])]
#[Sortable(['id', 'scheduled_date', 'applied_at', 'created_at'])]
class TreatmentApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'datetime',
            'applied_at' => 'datetime',
        ];
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }

    public function clinicalTreatment(): BelongsTo
    {
        return $this->belongsTo(ClinicalTreatment::class);
    }

    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class);
    }

    public function appliedBy(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'applied_by_id');
    }

    public function clinicHistory(): BelongsTo
    {
        return $this->belongsTo(ClinicHistory::class);
    }

    public function sanitaryPlan(): BelongsTo
    {
        return $this->belongsTo(SanitaryPlan::class);
    }

    public function kardexMovements(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(MovementKardex::class, 'event');
    }

    public function apply($user, int $quantityUsed): void
    {
        $this->update([
            'applied_at' => now(),
            'applied_by_id' => is_numeric($user) ? $user : $user->id,
            'quantity' => $quantityUsed,
        ]);

        if ($this->supply_id) {
            // Remove existing kardex movements for this event to prevent duplicates
            $this->kardexMovements()->delete();

            MovementKardex::create([
                'item_id' => $this->supply_id,
                'item_type' => (new Supply)->getMorphClass(),
                'type' => \App\Enums\MovementType::OUTCOME,
                'quantity' => $quantityUsed,
                'date' => now(),
                'event_id' => $this->id,
                'event_type' => $this->getMorphClass(),
            ]);
        }
    }

    public function unapply(): void
    {
        $this->update([
            'applied_at' => null,
            'applied_by_id' => null,
        ]);

        $this->kardexMovements()->delete();
    }
}
