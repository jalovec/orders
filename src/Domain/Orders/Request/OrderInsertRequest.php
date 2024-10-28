<?php

namespace App\Domain\Orders\Request;

use Symfony\Component\Validator\Constraints as Assert;

class OrderInsertRequest
{
    #[Assert\NotBlank(message: 'This value is mandatory')]
    #[Assert\Type('string', message: 'This value is not string')]
    public string $customerName;
    #[Assert\NotBlank(message: 'This value is mandatory')]
    #[Assert\Type('numeric', message: 'This value is not numeric')]
    public float $totalPrice;
}
