<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['text', 'livestock_id', 'commentable_id', 'commentable_type'])]
class Comment extends Model
{
    use SoftDeletes, HasFactory;

    public function livestock(): BelongsTo
    {
        return $this->belongsTo(Livestock::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
