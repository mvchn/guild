<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testProducts()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email' => 'jane_admin@symfony.com']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/adm/products');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddProduct()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email' => 'jane_admin@symfony.com']);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', '/adm/products/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
