<?php

namespace App\Enums;

enum RevisionResult: string
{
    case PREGNANT = 'pregnant';

    case EMPTY = 'empty';

    case WAITING = 'waiting';

    case HEAT = 'heat';
}
