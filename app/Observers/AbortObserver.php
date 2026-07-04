<?php

namespace App\Observers;

use App\Models\Abort;
use App\Traits\SyncsWithEvent;

class AbortObserver
{
    use SyncsWithEvent;

    /**
     * Handle the Abort "saved" event.
     */
    public function saved(Abort $abort): void
    {
        $this->syncEvent($abort, $abort->livestock_id, $abort->made_at);
    }

    /**
     * Handle the Abort "deleted" event.
     */
    public function deleted(Abort $abort): void
    {
        $this->deleteEvent($abort);
    }

    /**
     * Handle the Abort "restored" event.
     */
    public function restored(Abort $abort): void
    {
        $this->restoreEvent($abort);
    }
}
