<?php

namespace App\Tests\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    /**
     * @dataProvider getRoutes
     */
    public function testShowSuccess(string $route)
    {
        $client = static::createClient();

        $client->request('GET', $route);

        $this->assertResponseIsSuccessful();
    }

    public function testAddOrderSuccess()
    {
        $client = static::createClient();
        $orderRepository = static::$container->get(OrderRepository::class);

        $client->request('GET', '/product/1/order');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->submitForm('Save', [
            'order[name]' => 'name',
            'order[email]' => 'movchan@gmail.com',
        ]);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $order = $orderRepository->findOneBy(['name' => 'name', 'email' => 'movchan@gmail.com']);

        $client->request('GET', sprintf('/orders/%d', $order->getId()));
        $this->assertResponseIsSuccessful();

        $this->assertInstanceOf(Order::class, $order);
    }

    public function getRoutes(): iterable
    {
        yield '1' => ['/product/1'];
        yield 'list' =>  ['/products'];
    }


}
