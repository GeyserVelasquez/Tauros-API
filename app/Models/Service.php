<?php

namespace App\Models;

use App\Attributes\Includable;
use App\Observers\ServiceObserver;
use App\Traits\HasInclude;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['female_id', 'parentable_type', 'parentable_id', 'technician_id', 'service_type_id', 'made_at'])]
#[Includable(['female', 'parentable', 'technician', 'serviceType'])]
#[ObservedBy(ServiceObserver::class)]
class Service extends Model
{
    use SoftDeletes, HasFactory, HasInclude;

    protected function casts(): array
    {
        return [
            'made_at' => 'date',
        ];
    }

    public function female(): BelongsTo
    {
        return $this->belongsTo(Livestock::class, 'female_id');
    }

    public function parentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }
}
