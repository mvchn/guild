<?php

namespace App\Tests\Controller\Advertiser;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
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

    public function getRoutes(): iterable
    {
        yield 'dashboard' => [200, '/adm'];
        yield 'changelog' =>  [200, '/changelog'];
        yield 'notfound' => [404, '/adm/notfound'];
    }
}
