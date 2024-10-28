<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Orders\Dto\OrdersDto;
use App\Domain\Orders\Enum\OrdersStatusEnum;
use App\Domain\Orders\Service\OrdersService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class OrdersServiceTest extends TestCase
{
    public function testGenerateJson()
    {
        $order1 = new OrdersDto(
            1,
            'John Doe',
            new \DateTime('2023-10-27 14:30:00'),
            OrdersStatusEnum::NEW,
            150.00
        );

        $order2 = new OrdersDto(
            2,
            'Jane Smith',
            new \DateTime('2023-10-28 10:15:00'),
            OrdersStatusEnum::PROCESS,
            200.00
        );

        $orders = [$order1, $order2];

        $ordersServiceMock = $this->createMock(OrdersService::class);

        $expectedJsonContent = json_encode([
            [
                'id' => 1,
                'customerName' => 'John Doe',
                'orderDate' => '2023-10-27 14:30:00',
                'status' => 'NEW',
                'totalPrice' => '150.00 CZK'
            ],
            [
                'id' => 2,
                'customerName' => 'Jane Smith',
                'orderDate' => '2023-10-28 10:15:00',
                'status' => 'PROCESS',
                'totalPrice' => '200.00 CZK'
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $responseMock = new Response($expectedJsonContent);
        $responseMock->headers->set('Content-Type', 'application/json');
        $responseMock->headers->set('Content-Disposition', 'attachment; filename="orders_2023-10-27_19-41-03.json"');

        $ordersServiceMock
            ->expects($this->once())
            ->method('generateJson')
            ->with($this->equalTo($orders))
            ->willReturn($responseMock);

        $response = $ordersServiceMock->generateJson($orders);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($expectedJsonContent, $response->getContent());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertStringStartsWith('attachment; filename="orders_', $response->headers->get('Content-Disposition'));
        $this->assertStringEndsWith('.json"', $response->headers->get('Content-Disposition'));
        $this->assertCount(2, json_decode($response->getContent(), true));
    }
}

