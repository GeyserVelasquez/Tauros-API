<?php

namespace App\Sanitizers;

use App\Models\Service;

class ServiceSanitizer extends Sanitizer
{
    /**
     * Utility Class to sanitize Models' attributes.
     */
    public function __construct()
    {
        //
    }

    public function sanitize(Service $service): void
    {
        // Add basic sanitization if needed
    }
}
