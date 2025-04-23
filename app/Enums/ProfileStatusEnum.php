<?php

namespace App\Enums;

enum ProfileStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case WAITING = 'waiting';
}
