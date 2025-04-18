<?php
declare(strict_types=1);

namespace AlexPerez\CoffeeMachine\Tests\Integration\Order\Infrastructure\Controller;

use AlexPerez\CoffeeMachine\Tests\Integration\IntegrationTestCase;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\Attributes\Test;

class CreateOrderControllerTest extends IntegrationTestCase
{
    #[Test]
    public function it_should_create_an_order(): void
    {
        $this->client->request(
            'POST',
            '/api/orders',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['extra_hot' => false])
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('orderId', $responseData);
    }

    #[Test]
    public function it_should_create_an_extra_hot_order(): void
    {
        $this->client->request(
            'POST',
            '/api/orders',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['extraHot' => true])
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('orderId', $responseData);
    }
}
