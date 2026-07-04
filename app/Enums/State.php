<?php

namespace App\Enums;

enum State: string
{
    case HEALTHY = 'healthy';
    case SICK = 'sick';
    case TREATMENT = 'treatment';
    case QUARANTINE = 'quarantine';
}
