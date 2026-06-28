<?php

namespace App\Services;

use App\Attributes\Filterable;
use App\Attributes\Includable;
use App\Attributes\Sortable;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

class QueryBuilderService
{
    /**
     * Construye un QueryBuilder para una clase de modelo específica.
     */
    public function build(string $modelClass, $request = null): SpatieQueryBuilder
    {
        // 1. Intentar resolver una clase Query dedicada (ej. App\Http\Queries\LivestockQuery)
        $modelName = class_basename($modelClass);
        $customQueryClass = "App\\Http\\Queries\\{$modelName}Query";

        if (class_exists($customQueryClass)) {
            return new $customQueryClass($request ?? request());
        }

        // 2. Si no existe, configurar dinámicamente usando Reflection y Atributos
        return $this->buildFromAttributes($modelClass, $request);
    }

    /**
     * Construye un QueryBuilder optimizado para un único modelo (ej. Endpoint SHOW).
     */
    public function buildForModel(Model $model, $request = null): SpatieQueryBuilder
    {
        return $this->build(get_class($model), $request)
            ->where($model->getKeyName(), $model->getKey());
    }

    /**
     * Configuración dinámica mediante atributos.
     */
    private function buildFromAttributes(string $modelClass, $request = null): SpatieQueryBuilder
    {
        $query = SpatieQueryBuilder::for($modelClass, $request ?? request());
        $reflection = new ReflectionClass($modelClass);

        // Configurar Includes permitidos
        $includableAttr = $reflection->getAttributes(Includable::class);
        if (! empty($includableAttr)) {
            $query->allowedIncludes(...$includableAttr[0]->newInstance()->includes);
        }

        // Configurar Filtros permitidos
        $filterableAttr = $reflection->getAttributes(Filterable::class);
        if (! empty($filterableAttr)) {
            $query->allowedFilters(...$filterableAttr[0]->newInstance()->filters);
        }

        // Configurar Sorts permitidos
        $sortableAttr = $reflection->getAttributes(Sortable::class);
        if (! empty($sortableAttr)) {
            $query->allowedSorts(...$sortableAttr[0]->newInstance()->sorts);
        }

        return $query;
    }
}
