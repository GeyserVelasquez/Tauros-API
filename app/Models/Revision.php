<?php

namespace App\Models;

use App\Enums\RevisionResult;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['livestock_id', 'made_at', 'revision_result', 'revision_type_id', 'technician_id'])]
class Revision extends Model
{
    use SoftDeletes, HasFactory;

    protected function casts(): array
    {
        return [
            'made_at' => 'date',
            'revision_result' => RevisionResult::class,
        ];
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }

    public function revisionType(): BelongsTo
    {
        return $this->belongsTo(RevisionType::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }
}
