<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['certificate_id', 'livestock_id'])]
#[Includable(['certificate', 'livestock'])]
#[Filterable(['certificate_id', 'livestock_id'])]
#[Sortable(['id', 'created_at'])]
class LivestockCertificate extends Model
{
    use SoftDeletes;

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }
}
