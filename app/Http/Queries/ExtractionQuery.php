<?php

namespace App\Http\Queries;

use App\Models\Extraction;
use Spatie\QueryBuilder\AllowedFilter;

class ExtractionQuery extends BaseQuery
{
    public function __construct($request = null)
    {
        // 1. Inyectamos la subquery con withSum a la consulta base
        $subject = Extraction::query()->withSum('movements as quantity', 'quantity');

        // 2. Inicializamos el constructor del QueryBuilder de Spatie
        parent::__construct($subject, $request ?? request());

        // 3. Declaramos los filtros, ordenamientos e includes permitidos
        $this->allowedFilters(...[
            AllowedFilter::exact('batch_type'),
            AllowedFilter::exact('batch_id'),
            AllowedFilter::exact('technician_id'),
            AllowedFilter::exact('extraction_type_id'),
            'made_at'
        ]);

        $this->allowedIncludes(...['batch', 'technician', 'extractionType', 'movements']);

        // Habilitamos la columna virtual 'quantity' para que sea ordenable
        $this->allowedSorts(...['id', 'made_at', 'created_at', 'quantity']);
    }
}
