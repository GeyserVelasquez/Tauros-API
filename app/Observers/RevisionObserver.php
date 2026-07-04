<?php

namespace App\Observers;

use App\Models\Revision;
use App\Traits\SyncsWithEvent;

class RevisionObserver
{
    use SyncsWithEvent;

    /**
     * Handle the Revision "saved" event.
     */
    public function saved(Revision $revision): void
    {
        $this->syncEvent($revision, $revision->livestock_id, $revision->made_at);
    }

    /**
     * Handle the Revision "deleted" event.
     */
    public function deleted(Revision $revision): void
    {
        $this->deleteEvent($revision);
    }

    /**
     * Handle the Revision "restored" event.
     */
    public function restored(Revision $revision): void
    {
        $this->restoreEvent($revision);
    }
}
