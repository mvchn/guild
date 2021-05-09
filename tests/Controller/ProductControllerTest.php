<?php

namespace App\Tests\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private $productRepository;

    /**
     * @dataProvider getRoutes
     */
    public function testShowSuccess(string $route)
    {
        $client = static::createClient();
        $this->productRepository = static::$container->get(ProductRepository::class);

        $client->request('GET', $route);

        $this->assertResponseIsSuccessful();
    }

    public function testAddOrderSuccess()
    {
        $client = static::createClient();

        $orderRepository = static::$container->get(OrderRepository::class);
        $this->productRepository = static::$container->get(ProductRepository::class);

        $product = $this->productRepository->findOneBy(['title' => 'product123a']);

        $client->request('GET', sprintf('/product/%d', $product->getId()));
        $this->assertResponseIsSuccessful();

        $client->request('GET', sprintf('/stock/%d/order', $product->getStocks()->first()->getId()));
        $this->assertResponseIsSuccessful();

        $client->submitForm('Save', [
            'form[name]' => 'name',
            'form[email]' => 'movchan@gmail.com',
        ]);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $order = $orderRepository->findOneBy([], ['createdAt' => 'DESC']);

        $client->request('GET', sprintf('/orders/%d', $order->getId()));
        $this->assertResponseIsSuccessful();

        $this->assertInstanceOf(Order::class, $order);
    }

    public function getRoutes(): iterable
    {
        yield 'list' =>  ['/products'];
    }
}