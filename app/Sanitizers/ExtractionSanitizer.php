<?php

namespace App\Sanitizers;

use App\Models\Extraction;

class ExtractionSanitizer extends Sanitizer
{
    /**
     * Utility Class to sanitize Models' attributes.
     */
    public function __construct()
    {
        //
    }

    public function sanitize(Extraction $extraction): void
    {
        // Add any extraction-specific sanitization if needed.
        // For now, ensure dates are properly formatted if they were strings (handled by casts usually).
    }
}
