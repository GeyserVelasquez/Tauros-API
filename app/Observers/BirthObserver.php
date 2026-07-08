<?php

namespace App\Observers;

use App\Models\Birth;
use App\Sanitizers\BirthSanitizer;
use App\Traits\SyncsWithEvent;
use App\Validators\BirthValidator;

class BirthObserver
{
    use SyncsWithEvent;

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

    /**
     * Handle the Birth "saved" event.
     */
    public function saved(Birth $birth): void
    {
        $this->syncEvent($birth, $birth->mother_id, $birth->birth_date);
    }

    /**
     * Handle the Birth "deleted" event.
     */
    public function deleted(Birth $birth): void
    {
        $this->deleteEvent($birth);
    }

    /**
     * Handle the Birth "restored" event.
     */
    public function restored(Birth $birth): void
    {
        $this->restoreEvent($birth);
    }
}
