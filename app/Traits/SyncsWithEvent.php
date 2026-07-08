<?php

namespace App\Traits;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;

trait SyncsWithEvent
{
    /**
     * Sincroniza un evento en la tabla centralizada events.
     */
    protected function syncEvent(Model $model, int $livestockId, $date): void
    {
        Event::updateOrCreate(
            [
                'eventable_type' => $model->getMorphClass(),
                'eventable_id' => $model->id,
            ],
            [
                'livestock' => $livestockId,
                'made_at' => $date,
            ]
        );
    }

    /**
     * Elimina el evento asociado.
     */
    protected function deleteEvent(Model $model): void
    {
        $model->event()->delete();
    }

    /**
     * Restaura el evento asociado.
     */
    protected function restoreEvent(Model $model): void
    {
        $model->event()->restore();
    }
}
