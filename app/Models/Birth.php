<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use App\Observers\BirthObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['mother_id', 'birth_date', 'postbirth_revision_date', 'birth_type_id', 'technician_id'])]
#[Includable(['mother', 'birthType', 'technician', 'newborns'])]
#[Filterable(['mother_id', 'birth_type_id', 'technician_id', 'birth_date'])]
#[Sortable(['id', 'birth_date', 'postbirth_revision_date', 'created_at'])]
#[ObservedBy(BirthObserver::class)]
class Birth extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'postbirth_revision_date' => 'date',
        ];
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Livestock::class, 'mother_id');
    }

    public function birthType(): BelongsTo
    {
        return $this->belongsTo(BirthType::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function newborns(): HasMany
    {
        return $this->hasMany(Newborn::class);
    }
}
