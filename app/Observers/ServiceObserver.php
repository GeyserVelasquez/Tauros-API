<?php

namespace App\Observers;

use App\Models\Service;
use App\Sanitizers\ServiceSanitizer;
use App\Validators\ServiceValidator;

class ServiceObserver
{
    public function __construct(
        private ServiceSanitizer $sanitizer,
        private ServiceValidator $validator
    ) {}

    /**
     * Handle the Service "saving" event.
     */
    public function saving(Service $service): void
    {
        $this->sanitizer->sanitize($service);

        $this->validator->validate($service);
    }
}
