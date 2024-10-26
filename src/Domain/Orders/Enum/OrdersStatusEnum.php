<?php

namespace App\Domain\Orders\Enum;

enum OrdersStatusEnum: string
{
    case NEW = 'NEW';
    case PROCESS = 'PROCESS';
    case CLOSED = 'CLOSED';
}

