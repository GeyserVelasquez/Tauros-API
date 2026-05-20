<?php

namespace App\Traits;

use App\Attributes\Includable;
use Illuminate\Database\Eloquent\Builder;
use ReflectionClass;

trait HasInclude
{

    public function scopeWithIncludes(Builder $query, ?string $includes): Builder
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
        if (empty($includes)) {
            return [];
        }

        $reflection = new ReflectionClass($this);
        $attributes = $reflection->getAttributes(Includable::class);

        if (empty($attributes)) {
            return [];
        }

        $allowedIncludes = $attributes[0]->newInstance()->includes;

        $requested = explode(',', $includes);

        return array_intersect($requested, $allowedIncludes);
    }
}
