<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'attributes'])]
class ClinicalTreatment extends Model
{
    use SoftDeletes, HasFactory;



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
