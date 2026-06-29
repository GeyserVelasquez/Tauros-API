<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'attributes', 'supply_type_id'])]
#[Includable(['supplyType', 'clinicalTreatmentSupplies'])]
#[Filterable(['code', 'name', 'supply_type_id'])]
#[Sortable(['id', 'code', 'name', 'created_at'])]
class Supply extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'attributes' => 'array',
        ];
    }

    public function supplyType(): BelongsTo
    {
        return $this->belongsTo(SupplyType::class);
    }

    public function clinicalTreatmentSupplies(): HasMany
    {
        return $this->hasMany(ClinicalTreatmentSupply::class);
    }
}
