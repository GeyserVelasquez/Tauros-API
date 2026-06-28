<?php

namespace App\Observers;

use App\Models\Extraction;
use App\Sanitizers\ExtractionSanitizer;
use App\Validators\ExtractionValidator;

class ExtractionObserver
{
    public function __construct(
        private ExtractionSanitizer $sanitizer,
        private ExtractionValidator $validator
    ) {}

    /**
     * Handle the Extraction "saving" event.
     */
    public function saving(Extraction $extraction): void
    {
        $this->sanitizer->sanitize($extraction);

        $this->validator->validate($extraction);
    }
}
