<?php

namespace App\Observers;

use App\Models\Newborn;
use App\Sanitizers\NewbornSanitizer;
use App\Validators\NewbornValidator;

class NewbornObserver
{
    public function __construct(
        private NewbornSanitizer $sanitizer,
        private NewbornValidator $validator
    ) {}

    /**
     * Handle the Newborn "saving" event.
     */
    public function saving(Newborn $newborn): void
    {
        $this->sanitizer->sanitize($newborn);

        $this->validator->validate($newborn);
    }
}
