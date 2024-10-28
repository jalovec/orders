<?php

declare(strict_types=1);

namespace App\Domain\Orders\Service;

use App\Domain\Orders\Dto\OrdersDto;
use App\Domain\Orders\Enum\OrdersStatusEnum;
use App\Domain\Orders\Repository\OrdersRepository;
use App\Entity\Orders\Orders;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Autoconfigure(lazy: true)]
class OrdersService
{
    private OrdersRepository $ordersRepository;

    public function __construct(
        OrdersRepository $ordersRepository
    ) {
        $this->ordersRepository = $ordersRepository;
    }

    /**
     * @throws \Exception
     */
    public function getOrder(int $id): OrdersDto
    {
        $order = $this->ordersRepository->doFind($id);

        return OrdersDto::createFromEntity($order);
    }

    /**
     * @throws \Exception
     */
    public function deleteOrder(int $id): bool
    {
        $order = $this->ordersRepository->doFind($id);
        $this->ordersRepository->remove($order, true);

        return true;
    }

    /**
     * @param int $id
     * @param OrdersStatusEnum|null $status
     * @param float|null $totalPrice
     * @return bool
     * @throws \Exception
     */
    public function updateOrder(
        int $id,
        ?OrdersStatusEnum $status = null,
        ?float $totalPrice = null
    ): bool {
        $order = $this->ordersRepository->doFind($id);
        $order->setStatus($status);
        $order->setTotalPrice($totalPrice);
        $this->ordersRepository->save($order, true);

        return true;
    }


    /**
     * @throws \Exception
     */
    public function createOrder(
        string $customerName,
        float $totalPrice
    ): void {
        try {
            $order = new Orders(
                $customerName,
                new \DateTime(),
                OrdersStatusEnum::NEW,
                $totalPrice
            );
            $this->ordersRepository->save($order, true);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param OrdersDto[] $orders
     */
    public function generateCsv(array $orders): Response
    {
        ob_start();

        $outputBuffer = fopen('php://output', 'w');

        fputcsv($outputBuffer, ['ID', 'Customer name', 'Status', 'Order date', 'Total price']);

        foreach ($orders as $order) {
            fputcsv($outputBuffer, [
                $order->id,
                $order->customerName,
                $order->status->value,
                $order->orderDate->format('Y-m-d H:i:s'),
                $order->totalPrice . ' CZK',
            ]);
        }

        $csvContent = ob_get_clean();

        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders_' . $this->timestamp() . '.csv"');

        return $response;
    }

    /**
     * @param OrdersDto[] $orders
     */
    public function generateJson(array $orders): Response
    {
        $data = array_map(function ($order) {
            return [
                'id' => $order->id,
                'customerName' => $order->customerName,
                'orderDate' => $order->orderDate->format('Y-m-d H:i:s'),
                'status' => $order->status->value,
                'totalPrice' => $order->totalPrice . ' CZK',
            ];
        }, $orders);

        // Create the JSON response
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Create a response and set headers for download
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders_' . $this->timestamp() . '.json"');

        return $response;
    }

    private function timestamp(): string
    {
        return (new \DateTime())->format('Y-m-d_H-i-s');
    }

    public function findOrders(
        Request $request
    ): array {
        $id = (int)$request->query->get('id');
        $customerName = (string)$request->query->get('customerName');
        $status = (string)$request->query->get('status');
        $orders = $this->ordersRepository->findOrders($id, $customerName, $status);

        return array_map(fn($order) => OrdersDto::createFromEntity($order), $orders);
    }
}
