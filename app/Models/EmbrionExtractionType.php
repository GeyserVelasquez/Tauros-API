<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name'])]
#[Includable([''])]
#[Filterable(['code', 'name'])]
#[Sortable(['id', 'code', 'name', 'created_at'])]
class EmbrionExtractionType extends Model
{
    use HasFactory, SoftDeletes;
}
