<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepageSuccess()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/pricing');

        $this->assertResponseIsSuccessful();
    }

    public function testCalendarPageSuccess() : void
    {
        $client = static::createClient();
        $client->request('GET', '/calendar');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode(), $client->getRequest());
    }
}