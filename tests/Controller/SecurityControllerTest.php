<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageSuccess()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginUnknownUserFail()
    {
        $client = static::createClient();

        $testUser = null;

        $this->expectException(\LogicException::class);

        $client->loginUser($testUser);
    }

    public function testLoginNoUserFail()
    {
        $client = static::createClient();

        $client->request('GET', '/profile');

        $this->assertResponseStatusCodeSame(404);
    }
}
