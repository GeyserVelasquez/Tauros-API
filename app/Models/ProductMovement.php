<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\MovementType;

#[Fillable(['product_id', 'type', 'made_at', 'attributes'])]
class ProductMovement extends Model
{
    use SoftDeletes, HasFactory;

    protected function casts(): array
    {
        return [
            'made_at' => 'datetime',
            'attributes' => 'array',
            'type' => MovementType::class,
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
