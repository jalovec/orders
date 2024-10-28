<?php

declare(strict_types=1);

namespace App\Domain\Orders\Dto;

use App\Domain\Orders\Enum\OrdersStatusEnum;
use App\Entity\Orders\Orders;

class OrdersDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $customerName,
        public readonly \DateTime $orderDate,
        public readonly OrdersStatusEnum $status,
        public readonly float $totalPrice,
    ) {
    }

    public static function createFromEntity(
        Orders $order
    ): self {
        return new self(
            $order->getId(),
            $order->getCustomerName(),
            $order->getOrderDate(),
            $order->getStatus(),
            $order->getTotalPrice(),
        );
    }

    public static function createFromScalars(
        int $id,
        string $customerName,
        \DateTime $orderDate,
        OrdersStatusEnum $status,
        float $totalPrice
    ): self {
        return new self(
            $id,
            $customerName,
            $orderDate,
            $status,
            $totalPrice,
        );
    }
}
