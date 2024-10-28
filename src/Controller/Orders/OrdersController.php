<?php

declare(strict_types=1);

namespace App\Controller\Orders;

use App\Domain\Orders\Enum\OrdersStatusEnum;
use App\Domain\Orders\Request\OrderInsertRequest;
use App\Domain\Orders\Request\OrderUpdateRequest;
use App\Domain\Orders\Service\OrdersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class OrdersController extends AbstractController
{
    private OrdersService $ordersService;

    public function __construct(OrdersService $ordersService)
    {
        $this->ordersService = $ordersService;
    }

    /* ====================== Display/Read Actions ====================== */

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): RedirectResponse
    {
        return new RedirectResponse($this->generateUrl('app_login'));
    }

    #[Route('/orders', name: 'getOrders', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function listOrders(Request $request): Response
    {
        $orders = $this->ordersService->findOrders($request);

        return $this->render(
            'orders/list.html.twig',
            [
                'orders' => $orders,
                'statuses' => OrdersStatusEnum::cases()
            ]
        );
    }

    #[Route('/orders/create', name: 'createOrderDialog', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createOrderDialog(): Response
    {
        return $this->render(
            'orders/create.html.twig',
            [
                'method' => 'post'
            ]
        );
    }

    /**
     * @throws \Exception
     */
    #[Route('/orders/update/{id}', name: 'updateOrderDialog', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateOrderDialog(int $id): Response
    {
        $order = $this->ordersService->getOrder($id);

        return $this->render(
            'orders/create.html.twig',
            [
                'order' => $order,
                'method' => 'put',
                'statuses' => OrdersStatusEnum::cases()
            ]
        );
    }

    /**
     * @throws \Exception
     */
    #[Route('/orders/{id}', name: 'getOrder', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getOrder(int $id): Response
    {
        $order = $this->ordersService->getOrder($id);

        return $this->render(
            'orders/item.html.twig',
            [
                'order' => $order
            ]
        );
    }

    /* ====================== Data Modification Actions ====================== */

    /**
     * @throws \Exception
     */
    #[Route('/orders', name: 'createOrder', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createOrder(
        #[MapRequestPayload] OrderInsertRequest $request
    ): RedirectResponse {
        $this->ordersService->createOrder(
            $request->customerName,
            $request->totalPrice
        );

        return new RedirectResponse($this->generateUrl('getOrders'));
    }

    /**
     * @throws \Exception
     */
    #[Route('/orders/{id}', name: 'updateOrder', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateOrder(
        int $id,
        #[MapRequestPayload] OrderUpdateRequest $request
    ): RedirectResponse {
        $this->ordersService->updateOrder(
            $id,
            OrdersStatusEnum::from($request->status),
            $request->totalPrice
        );

        return new RedirectResponse($this->generateUrl('getOrders'));
    }

    /**
     * @throws \Exception
     */
    #[Route('/orders/{id}', name: 'deleteOrder', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteOrder(int $id): Response
    {
        $this->ordersService->deleteOrder($id);

        return $this->json(1);
    }

    /* ====================== Export Actions ====================== */

    #[Route('/orders/export/csv', name: 'orders_export_csv', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function exportCsv(Request $request): Response
    {
        $orders = $this->ordersService->findOrders($request);

        return $this->ordersService->generateCsv($orders);
    }

    #[Route('/orders/export/json', name: 'orders_export_json')]
    #[IsGranted('ROLE_ADMIN')]
    public function exportJson(Request $request): Response
    {
        $orders = $this->ordersService->findOrders($request);

        return $this->ordersService->generateJson($orders);
    }
}
