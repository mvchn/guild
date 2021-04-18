<?php

namespace App\Tests\Event;

use App\Event\ProductSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class ProductSubscriberTest extends TestCase
{
    private $subscriber;

    protected function setUp(): void
    {
        $security = $this->createMock(Security::class);
        $this->subscriber = new ProductSubscriber($security);
    }

    public function testValidateUsername(): void
    {
        $events = $this->subscriber::getSubscribedEvents();

        $this->assertEquals([
            'product.new' => 'onStoreProduct',
        ], $events);
    }


}
