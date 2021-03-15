<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageSuccess()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginSuccess()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy([]);

        $client->loginUser($testUser);
        $client->request('GET', '/profile');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', sprintf('Profile %s', $testUser->getUsername()));
    }

    public function testLoginFail()
    {
        $client = static::createClient();

        $client->request('GET', '/profile');

        $this->assertResponseStatusCodeSame(404);
    }
}
