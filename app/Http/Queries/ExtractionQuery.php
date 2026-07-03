<?php

namespace App\Http\Queries;

use App\Models\EmbrionBatch;
use App\Models\Extraction;
use App\Models\SemenBatch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class ExtractionQuery extends BaseQuery
{
    public function __construct($request = null)
    {
        $subject = Extraction::query()->withSum('movements as quantity', 'quantity');

        parent::__construct($subject, $request ?? request());

        $this->allowedFilters(...[
            AllowedFilter::exact('geneticable_type'),
            AllowedFilter::exact('geneticable_id'),
            AllowedFilter::exact('technician_id'),
            AllowedFilter::exact('extraction_type_id'),
            'made_at',
            AllowedFilter::callback('geneticable.code', function (Builder $query, $value) {
                $query->whereHasMorph('geneticable', [
                    SemenBatch::class,
                    EmbrionBatch::class,
                ], function (Builder $query) use ($value) {
                    $query->where('code', 'like', "%{$value}%");
                });
            })
        ]);

        $this->allowedIncludes(...[
            'technician',
            'extractionType',
            'movements',
            AllowedInclude::callback('geneticable', function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    SemenBatch::class => ['livestock'],
                    EmbrionBatch::class => ['mother', 'father'],
                ]);
            })
        ]);

        $this->allowedSorts(...['id', 'made_at', 'created_at', 'quantity']);
    }
}
