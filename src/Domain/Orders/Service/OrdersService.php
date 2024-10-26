<?php

declare(strict_types=1);

namespace App\Domain\Orders\Service;

use App\Domain\Orders\Dto\OrdersDto;
use App\Domain\Orders\Enum\OrdersStatusEnum;
use App\Domain\Orders\Repository\OrdersRepository;
use App\Entity\Orders\Orders;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class OrdersService
{
    public function __construct(
        OrdersRepository $ordersRepository
    )
    {
    }

    /**
     * @return OrdersDto[]
     */
    public function listOrders(): array
    {
        $orders = $this->ordersRepository->findAll();

        $list = [];
        foreach ($orders as $order) {
            $list[] = OrdersDto::createFromEntity($order);
        }

        return $list;
    }

    /**
     * @throw \Exception
     */
    public function getOrder(int $id): array
    {
        $order = $this->ordersRepository->doFind($id);

        return OrdersDto::createFromEntity($order);
    }

    /**
     * @throw \Exception
     */
    public function deleteOrder(int $id): bool
    {
        $order = $this->ordersRepository->doFind($id);
        $this->ordersRepository->remove($order, true);

        return true;
    }

    /**
     * @throw \Exception
     */
    public function updateOrder(
        int               $id,
        ?OrdersStatusEnum $status = null,
        ?float            $totalPrice = null
    ): bool
    {
        $order = $this->ordersRepository->doFind($id);
        if ($status) {
            $order->setStatus($status);
        }
        if ($totalPrice) {
            $order->setTotalPrice($totalPrice);
        }
        $this->ordersRepository->save($order, true);

        return true;
    }


    /**
     * @throws \Exception
     */
    public function createOrder(
        string $customerName,
        float  $totalPrice
    ): bool
    {
        try {
            $order = new Orders(
                $customerName,
                new DateTime(),
                OrdersStatusEnum::NEW,
                $totalPrice
            );
            $this->ordersRepository->save($order, true);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return true;
    }
}
