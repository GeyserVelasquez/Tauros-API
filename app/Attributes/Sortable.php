<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Sortable
{
    public function __construct(public array $sorts) {}
}
