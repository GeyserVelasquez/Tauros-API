<?php

namespace App\Http\Queries;

use App\Models\Livestock;
use Spatie\QueryBuilder\AllowedFilter;

class LivestockQuery extends BaseQuery
{
    public function __construct($request = null)
    {
        parent::__construct(Livestock::query(), $request ?? request());

        $this->allowedIncludes(...[
            'entryCause', 'state', 'breed', 'color', 'classification',
            'owner', 'technician', 'batch', 'father', 'mother',
            'adoptiveMother', 'receivingMother', 'currentBatchMovement',
        ])
            ->allowedFilters(...[
                'name',
                'brand_number',
                'electronic_code',
                AllowedFilter::exact('state_id'),
                AllowedFilter::exact('breed_id'),
                AllowedFilter::exact('animal_category'),
                AllowedFilter::scope('born_after'),
            ])
            ->allowedSorts(...[
                'id',
                'name',
                'entry_date',
                'birth_date',
                'created_at',
            ])
            ->defaultSort('-created_at');
    }
}
