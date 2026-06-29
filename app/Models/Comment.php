<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['text', 'livestock_id', 'commentable_id', 'commentable_type'])]
#[Includable(['livestock', 'commentable'])]
#[Filterable(['livestock_id', 'commentable_id', 'commentable_type'])]
#[Sortable(['id', 'created_at'])]
class Comment extends Model
{
    use HasFactory, SoftDeletes;

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
