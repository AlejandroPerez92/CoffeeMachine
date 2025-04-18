<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Tests\Integration\Order\Infrastructure\Controller;

use AlexPerez\CoffeeMachine\Shared\Domain\Order\OrderId;
use AlexPerez\CoffeeMachine\Tests\Integration\IntegrationTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\Attributes\Test;

class AddOrderLineControllerTest extends IntegrationTestCase
{
    private string $orderId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderId = OrderId::create()->value();

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
    }

    #[Test]
    public function it_should_add_a_line_to_an_order(): void
    {
        $this->client->request(
            'POST',
            '/api/orders/' . $this->orderId . '/lines',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['product_name' => 'coffee', 'units' => 1])
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    #[Test]
    public function it_should_return_error_for_invalid_product(): void
    {
        $this->client->request(
            'POST',
            '/api/orders/' . $this->orderId . '/lines',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['product_name' => 'invalid', 'units' => 1])
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
    }

    #[Test]
    public function it_should_return_error_for_invalid_units(): void
    {
        $this->client->request(
            'POST',
            '/api/orders/' . $this->orderId . '/lines',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['product_name' => 'coffee', 'units' => 10])
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
    }
}
