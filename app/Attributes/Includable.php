<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Includable
{
    /**
     * @param array<int, string> $includes Lista de relaciones permitidas
     */
    public function __construct(public array $includes)
    {
    }
}
