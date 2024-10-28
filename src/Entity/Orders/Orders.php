<?php

declare(strict_types=1);

namespace App\Entity\Orders;

use App\Domain\Orders\Enum\OrdersStatusEnum;
use App\Domain\Orders\Repository\OrdersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Brick\Money\Money;

#[ORM\Table(name: 'orders')]
#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Column(
        options: [
        'unsigned' => true,
        ]
    )]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(
        name: 'customer_name',
        type: Types::STRING,
        length: 255,
    )]
    private string $customerName;

    #[ORM\Column(
        name: 'order_date',
        type: Types::DATETIME_MUTABLE,
    )]
    private \DateTime $orderDate;

    #[ORM\Column(
        name: 'status',
        type: Types::STRING,
        enumType: OrdersStatusEnum::class,
    )]
    private OrdersStatusEnum $status;


    #[ORM\Column(
        name: 'total_price',
        type: Types::FLOAT,
        options: [
            'default' => 0,
        ]
    )]
    private float $totalPrice;

    /**
     * @throws \Exception
     */
    public function __construct(
        string $customerName,
        \DateTime $orderDate,
        OrdersStatusEnum $status,
        float $totalPrice
    ) {
        $this->customerName = $customerName;
        $this->orderDate = $orderDate;
        $this->status = $status;
        $this->totalPrice = $totalPrice;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getOrderDate(): \DateTime
    {
        return $this->orderDate;
    }

    public function getStatus(): OrdersStatusEnum
    {
        return $this->status;
    }

    public function setStatus(OrdersStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }
}
