<?php

namespace App\Observers;

use App\Models\MovementKardex;
use App\Sanitizers\MovementKardexSanitizer;
use App\Validators\MovementKardexValidator;

class MovementKardexObserver
{
    public function __construct(
        private MovementKardexSanitizer $sanitizer,
        private MovementKardexValidator $validator
    ) {}

    /**
     * Handle the MovementKardex "saving" event.
     */
    public function saving(MovementKardex $movementKardex): void
    {
        $this->sanitizer->sanitize($movementKardex);

        $this->validator->validate($movementKardex);
    }
}
