<?php

namespace App\Sanitizers;

use App\Models\Birth;

class BirthSanitizer extends Sanitizer
{
    /**
     * Utility Class to sanitize Models' attributes.
     */
    public function __construct()
    {
        //
    }

    public function sanitize(Birth $birth): void
    {
        // Add basic sanitization if needed
    }
}
