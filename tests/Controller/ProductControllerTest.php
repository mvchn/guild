<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    /**
     * @dataProvider getRoutes
     */
    public function testRoutesSuccess(int $expected, string $route)
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email' => 'jane_admin@symfony.com']);

        $client->loginUser($testUser);

        $client->request('GET', $route);

        $this->assertEquals($expected, $client->getResponse()->getStatusCode());
    }

    public function testAddSuccess()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);
        $productRepository = static::$container->get(ProductRepository::class);

        $testUser = $userRepository->findOneBy(['email' => 'jane_admin@symfony.com']);

        $client->loginUser($testUser);

        $client->request('POST', '/adm/products/new');
        $client->submitForm('Create', [
            'product[title]' => 'test',
        ]);

        $product = $productRepository->findOneBy(['title'=> 'test']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(Product::class, $product);

        $client->request('GET', sprintf('/adm/products/%d', $product->getId()));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', sprintf('/adm/products/%d/edit', $product->getId()));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->submitForm('Create', [
            'product[title]' => 'test_test',
        ]);

        $product = $productRepository->findOneBy(['title'=> 'test_test']);
        $this->assertInstanceOf(Product::class, $product);

    }

    public function getRoutes(): iterable
    {
        yield 'list' => [200, '/adm/products'];
        yield 'new' =>  [200, '/adm/products/new'];
        yield 'fail' => [404, '/adm/products/ff'];
    }
}
