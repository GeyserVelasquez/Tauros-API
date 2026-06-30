<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use App\Traits\HasComment;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['made_at', 'abort_type_id', 'livestock_id', 'technician_id'])]
#[Includable(['abortType', 'livestock', 'technician'])]
#[Filterable(['abort_type_id', 'livestock_id', 'technician_id', 'made_at', 'livestock.brand_number'])]
#[Sortable(['id', 'made_at', 'created_at'])]
class Abort extends Model
{
    use HasComment, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'made_at' => 'date',
        ];
    }

    public function abortType(): BelongsTo
    {
        return $this->belongsTo(AbortType::class);
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }
}
