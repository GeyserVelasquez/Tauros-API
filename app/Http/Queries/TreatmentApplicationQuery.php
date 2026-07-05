<?php

namespace App\Http\Queries;

use App\Models\TreatmentApplication;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class TreatmentApplicationQuery extends BaseQuery
{
    public function __construct($request = null)
    {
        $subject = TreatmentApplication::query();

        parent::__construct($subject, $request ?? request());

        $this->allowedFilters(...[
            AllowedFilter::exact('livestock_id'),
            AllowedFilter::exact('clinical_treatment_id'),
            AllowedFilter::exact('supply_id'),
            AllowedFilter::exact('applied_by_id'),
            AllowedFilter::exact('clinic_history_id'),
            AllowedFilter::exact('sanitary_plan_id'),
            AllowedFilter::exact('scheduled_date'),
            AllowedFilter::callback('scheduled_date_between', function (Builder $query, $value) {
                if (is_string($value)) {
                    $parts = explode(',', $value);
                    if (count($parts) === 2) {
                        $query->whereBetween('scheduled_date', [trim($parts[0]), trim($parts[1])]);
                    }
                }
            }),
        ]);

        $this->allowedIncludes(...[
            'livestock',
            'clinicalTreatment',
            'supply',
            'appliedBy',
            'clinicHistory',
            'sanitaryPlan',
        ]);

        $this->allowedSorts(...[
            'id',
            'scheduled_date',
            'applied_at',
            'created_at',
        ]);
    }
}
