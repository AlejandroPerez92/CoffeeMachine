<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Tests\Integration\Order\Infrastructure\Controller;

use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\Attributes\Test;

class PayOrderControllerTest extends IntegrationTestCase
{
    private string $orderId;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an order first
        $this->client->request(
            'POST',
            '/api/orders',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['extra_hot' => false])
        );
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->orderId = $responseData['orderId'];

        // Add a coffee to the order
        $this->client->request(
            'POST',
            '/api/orders/' . $this->orderId . '/lines',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['product_name' => 'coffee', 'units' => 1])
        );
    }

    #[Test]
    public function it_should_pay_an_order(): void
    {
        $this->client->request(
            'POST',
            '/api/orders/' . $this->orderId . '/payment',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['amount' => 100])
        );

        $this->assertEquals(Response::HTTP_ACCEPTED, $this->client->getResponse()->getStatusCode());
    }

    #[Test]
    public function it_should_return_error_for_insufficient_amount(): void
    {
        $this->client->request(
            'POST',
            '/api/orders/' . $this->orderId . '/payment',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['amount' => 10])
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
    }
}
