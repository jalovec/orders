<?php

namespace App\Domain\Orders\Request;

use App\Domain\Orders\Enum\OrdersStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

class OrderUpdateRequest
{
    #[Assert\Choice(
        callback: [OrdersStatusEnum::class, 'supportedStatus'],
        message: 'Status is not valid'
    )]
    public string $status;
    #[Assert\NotBlank(message: 'This value is mandatory')]
    #[Assert\Type('numeric', message: 'This value is not numeric')]
    public float $totalPrice;
}
