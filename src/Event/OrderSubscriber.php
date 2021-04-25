<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OrderEvent::CONFIRMED => 'onConfirm',
        ];
    }

    public function onConfirm(OrderEvent $event)
    {
        $order = $event->getOrder();
        $order->setStatus('confirmed');
    }
}

