<?php

namespace App\Observers;

use App\Models\Birth;
use App\Sanitizers\BirthSanitizer;
use App\Validators\BirthValidator;

class BirthObserver
{
    public function __construct(
        private BirthSanitizer $sanitizer,
        private BirthValidator $validator
    ) {}

    /**
     * Handle the Birth "saving" event.
     */
    public function saving(Birth $birth): void
    {
        $this->sanitizer->sanitize($birth);

        $this->validator->validate($birth);
    }
}
