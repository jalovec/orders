<?php

declare(strict_types=1);

namespace App\Controller\Orders;

use App\Domain\Orders\Service\OrdersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    private OrdersService $ordersService;

    public function __construct(
        OrdersService $ordersService
    )
    {
        $this->ordersService = $ordersService;
    }

    /**
     * @throws \Exception
     */
    #[Route('/orders', name: 'getOrders', methods: ['POST'])]
    public function createOrder(Request $request): Response
    {
        $this->ordersService->createOrder(
            $request->get('customerName'),
            (float)$request->get('totalPrice')
        );

        return $this->json(1);
    }

    #[Route('/orders', name: 'getOrders', methods: ['GET'])]
    public function listOrders(): Response
    {
        $orders = $this->ordersService->listOrders();

        return $this->render('orders/list.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/orders/update/{id}', name: 'updateOrderDialog', methods: ['GET'])]
    public function updateOrderDialog(int $id): Response
    {
        $order = $this->ordersService->getOrder($id);

        return $this->render('orders/create.html.twig', [
            'order' => $order,
            'method' => 'put'
        ]);
    }

    #[Route('/orders/create', name: 'createOrderDialog', methods: ['GET'])]
    public function createOrderDialog(): Response
    {
        return $this->render('orders/create.html.twig', [
            'method' => 'post'
        ]);
    }

    #[Route('/orders/{id}', name: 'getOrder', methods: ['GET'])]
    public function getOrder(int $id): Response
    {
        $order = $this->ordersService->getOrder($id);

        return $this->render('orders/item.html.twig', [
            'order' => $order
        ]);
    }

    #[Route('/orders/{id}', name: 'deleteOrder', methods: ['DELETE'])]
    public function deleteOrder(int $id): Response
    {
        $this->ordersService->deleteOrder($id);

        return $this->json(1);
    }

    #[Route('/orders/{id}', name: 'updateOrder', methods: ['PUT'])]
    public function updateOrder(int $id, Request $request): Response
    {
        $this->ordersService->updateOrder(
            $id,
            $request->get('status'),
            (float)$request->get('totalPrice')
        );

        return $this->json(1);
    }
}
