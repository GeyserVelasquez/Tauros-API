<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['certificate_number', 'issue_date', 'expiry_date', 'file_path'])]
class Certificate extends Model
{
    use SoftDeletes, HasFactory;

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
