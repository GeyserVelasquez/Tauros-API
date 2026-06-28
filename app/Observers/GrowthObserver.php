<?php

namespace App\Observers;

use App\Models\Growth;
use App\Sanitizers\GrowthSanitizer;
use App\Validators\GrowthValidator;

class GrowthObserver
{
    public function __construct(
        private GrowthSanitizer $sanitizer,
        private GrowthValidator $validator
    ) {}

    /**
     * Handle the Growth "saving" event.
     */
    public function saving(Growth $growth): void
    {
        $this->sanitizer->sanitize($growth);

        $this->validator->validate($growth);
    }
}
