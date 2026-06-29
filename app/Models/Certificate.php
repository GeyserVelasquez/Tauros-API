<?php

namespace App\Models;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['certificate_number', 'issue_date', 'expiry_date', 'file_path'])]
#[Includable(['livestock'])]
#[Filterable(['certificate_number', 'issue_date', 'expiry_date'])]
#[Sortable(['id', 'certificate_number', 'issue_date', 'expiry_date', 'created_at'])]
class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function livestock(): BelongsToMany
    {
        return $this->belongsToMany(Livestock::class, 'livestock_certificates');
    }
}
