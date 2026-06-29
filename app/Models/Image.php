<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'path', 'description', 'livestock_id'])]
#[Includable(['livestock'])]
#[Filterable(['name', 'livestock_id'])]
#[Sortable(['id', 'name', 'created_at'])]
class Image extends Model
{
    use HasFactory, SoftDeletes;

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }
}
