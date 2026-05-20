<?php

namespace App\Observers;

use App\Models\Livestock;
use App\Sanitizers\LivestockSanitizer;
use App\Validators\LivestockValidator;

class LivestockObserver
{
    public function __construct(
        private LivestockSanitizer $sanitizer,
        private LivestockValidator $validator
    ) {}

    /**
     * Handle the Livestock "saving" event.
     */
    public function saving(Livestock $livestock): void
    {
        $this->sanitizer->sanitize($livestock);

        $this->validator->validate($livestock);
    }

    /**
     * Handle the Livestock "created" event.
     */
    public function created(Livestock $livestock): void
    {

    }

    /**
     * Handle the Livestock "updated" event.
     */
    public function updated(Livestock $livestock): void
    {
        //
    }

    /**
     * Handle the Livestock "deleted" event.
     */
    public function deleted(Livestock $livestock): void
    {
        //
    }

    /**
     * Handle the Livestock "restored" event.
     */
    public function restored(Livestock $livestock): void
    {
        //
    }

    /**
     * Handle the Livestock "force deleted" event.
     */
    public function forceDeleted(Livestock $livestock): void
    {
        //
    }
}
