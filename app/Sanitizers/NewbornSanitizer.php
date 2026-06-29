<?php

namespace App\Sanitizers;

use App\Models\Newborn;

class NewbornSanitizer extends Sanitizer
{
    /**
     * Utility Class to sanitize Models' attributes.
     */
    public function __construct()
    {
        //
    }

    public function sanitize(Newborn $newborn): void
    {
        // Add basic sanitization if needed
    }
}
