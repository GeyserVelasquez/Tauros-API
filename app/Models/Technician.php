<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'telephone'])]
#[Includable(['livestock', 'semenBatches', 'embrionBatches', 'clinicHistories', 'births', 'services', 'revisions', 'teasings', 'extractions'])]
#[Filterable(['code', 'name', 'telephone'])]
#[Sortable(['id', 'code', 'name', 'created_at'])]
class Technician extends Model
{
    use HasFactory, SoftDeletes;

    public function livestock(): HasMany
    {
        return $this->hasMany(Livestock::class);
    }

    public function semenBatches(): HasMany
    {
        return $this->hasMany(SemenBatch::class);
    }

    public function embrionBatches(): HasMany
    {
        return $this->hasMany(EmbrionBatch::class);
    }

    public function clinicHistories(): HasMany
    {
        return $this->hasMany(ClinicHistory::class);
    }

    public function births(): HasMany
    {
        return $this->hasMany(Birth::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class);
    }

    public function teasings(): HasMany
    {
        return $this->hasMany(Teasing::class);
    }

    public function extractions(): HasMany
    {
        return $this->hasMany(Extraction::class);
    }
}
