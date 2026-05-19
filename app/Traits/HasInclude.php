<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasInclude
{

    public function scopeIncluded(Builder $query, ?string $includes): Builder
    {
        $validIncludes = $this->parseIncludes($includes);

        return empty($validIncludes) ? $query : $query->with($validIncludes);
    }

    public function loadIncludes(?string $includes): self
    {
        $validIncludes = $this->parseIncludes($includes);

        if (!empty($validIncludes)) {
            $this->load($validIncludes);
        }

        return $this;
    }

    protected function parseIncludes(?string $includes): array
    {
        if (empty($includes) || !property_exists($this, 'allowIncludes')) {
            return [];
        }

        $requested = explode(',', $includes);

        return array_intersect($requested, $this->allowIncludes);
    }
}
