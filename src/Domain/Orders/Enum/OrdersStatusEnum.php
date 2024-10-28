<?php

namespace App\Domain\Orders\Enum;

enum OrdersStatusEnum: string
{
    case NEW = 'NEW';
    case PROCESS = 'PROCESS';
    case CLOSED = 'CLOSED';

    /**
     * @return string[]
     */
    public static function supportedStatus(): array
    {
        return [
            self::NEW->value,
            self::PROCESS->value,
            self::CLOSED->value
        ];
    }
}
