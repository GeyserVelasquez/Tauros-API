<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use App\Enums\AnimalCategory;
use App\Observers\LivestockObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'brand_number', 'electronic_code', 'name', 'entry_date', 'birth_date',
    'general_comment', 'tits', 'is_enabled', 'is_alive', 'entry_cause_id',
    'state_id', 'animal_category', 'breed_id', 'color_id', 'classification_id',
    'owner_id', 'technician_id', 'father_id', 'mother_id',
    'adoptive_mother_id', 'receiving_mother_id',
])]

#[Includable([
    'entryCause', 'state', 'breed', 'color', 'classification', 'owner',
    'technician', 'batch', 'father', 'mother', 'adoptiveMother',
    'receivingMother', 'currentBatchMovement',
])]

#[Filterable(['name', 'brand_number', 'electronic_code', 'state_id', 'breed_id'])]
#[Sortable(['id', 'name', 'entry_date', 'birth_date', 'created_at'])]

#[ObservedBy([LivestockObserver::class])]
class Livestock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'livestock';

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'birth_date' => 'date',
            'is_enabled' => 'boolean',
            'is_alive' => 'boolean',
            'animal_category' => AnimalCategory::class,
        ];
    }

    public function entryCause(): BelongsTo
    {
        return $this->belongsTo(EntryCause::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function father(): BelongsTo
    {
        return $this->belongsTo(Livestock::class, 'father_id');
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Livestock::class, 'mother_id');
    }

    public function adoptiveMother(): BelongsTo
    {
        return $this->belongsTo(Livestock::class, 'adoptive_mother_id');
    }

    public function receivingMother(): BelongsTo
    {
        return $this->belongsTo(Livestock::class, 'receiving_mother_id');
    }

    public function childrenAsFather(): HasMany
    {
        return $this->hasMany(Livestock::class, 'father_id');
    }

    public function childrenAsMother(): HasMany
    {
        return $this->hasMany(Livestock::class, 'mother_id');
    }

    public function semenBatches(): HasMany
    {
        return $this->hasMany(SemenBatch::class);
    }

    public function embrionBatchesAsMother(): HasMany
    {
        return $this->hasMany(EmbrionBatch::class, 'mother_id');
    }

    public function embrionBatchesAsFather(): HasMany
    {
        return $this->hasMany(EmbrionBatch::class, 'father_id');
    }

    public function clinicHistories(): HasMany
    {
        return $this->hasMany(ClinicHistory::class);
    }

    public function births(): HasMany
    {
        return $this->hasMany(Birth::class, 'mother_id');
    }

    public function newborns(): HasMany
    {
        return $this->hasMany(Newborn::class);
    }

    public function servicesAsFemale(): HasMany
    {
        return $this->hasMany(Service::class, 'female_id');
    }

    public function servicesAsParentable(): MorphMany
    {
        return $this->morphMany(Service::class, 'parentable');
    }

    public function growths(): HasMany
    {
        return $this->hasMany(Growth::class);
    }

    public function aborts(): HasMany
    {
        return $this->hasMany(Abort::class);
    }

    public function milkings(): HasMany
    {
        return $this->hasMany(Milking::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(Outcome::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class);
    }

    public function teasings(): HasMany
    {
        return $this->hasMany(Teasing::class);
    }

    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class, 'livestock_certificates');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'origin');
    }

    public function batchMovements(): HasMany
    {
        return $this->hasMany(BatchMovement::class);
    }

    public function currentBatchMovement(): HasOne
    {
        return $this->hasOne(BatchMovement::class)->latestOfMany();
    }

    public function batches(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_movements')
            ->withTimestamps();
    }

    public function scopeBornAfter(Builder $query, string $date): Builder
    {
        return $query->where('birth_date', '>=', $date);
    }
}
