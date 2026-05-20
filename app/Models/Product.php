<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['code', 'name','description', 'attributes', 'product_type_id'])]
class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected function casts(): array
    {
        return [
            'attributes' => 'array',
        ];
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function productMovements(): HasMany
    {
        return $this->hasMany(ProductMovement::class);
    }
}
