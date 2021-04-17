<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testSuccess()
    {
        $client = static::createClient();

        $client->request('GET', '/product/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


}
