<?php

namespace App\Observers;

use App\Models\ClinicHistory;
use App\Sanitizers\ClinicHistorySanitizer;
use App\Validators\ClinicHistoryValidator;

class ClinicHistoryObserver
{
    public function __construct(
        private ClinicHistorySanitizer $sanitizer,
        private ClinicHistoryValidator $validator
    ) {}

    /**
     * Handle the ClinicHistory "saving" event.
     */
    public function saving(ClinicHistory $clinicHistory): void
    {
        $this->sanitizer->sanitize($clinicHistory);

        $this->validator->validate($clinicHistory);
    }
}
