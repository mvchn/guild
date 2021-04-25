<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class OrderSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderEvent::CONFIRMED => 'onConfirm',
        ];
    }

    public function onConfirm()
    {

    }
}

