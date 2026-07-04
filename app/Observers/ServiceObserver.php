<?php

namespace App\Observers;

use App\Models\Service;
use App\Sanitizers\ServiceSanitizer;
use App\Traits\SyncsWithEvent;
use App\Validators\ServiceValidator;

class ServiceObserver
{
    use SyncsWithEvent;

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

    /**
     * Handle the Service "saved" event.
     */
    public function saved(Service $service): void
    {
        $this->syncEvent($service, $service->female_id, $service->made_at);
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        $this->deleteEvent($service);
    }

    /**
     * Handle the Service "restored" event.
     */
    public function restored(Service $service): void
    {
        $this->restoreEvent($service);
    }
}
