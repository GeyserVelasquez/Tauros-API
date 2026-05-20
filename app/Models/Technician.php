<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'telephone'])]
class Technician extends Model
{
    use SoftDeletes, HasFactory;



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
