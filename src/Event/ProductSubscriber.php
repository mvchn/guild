<?php

namespace App\Event;

use App\Entity\Product;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class ProductSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvent::NEW => 'onStoreProduct',
            ProductEvent::SHOW_ORDER => 'onShowOrder',
            ProductEvent::CREATE_ORDER => 'onCreateOrder',
        ];
    }

    public function onStoreProduct(ProductEvent $event): Product
    {
        $product = $event->getProduct();

        $product->setCreator($this->security->getUser());

        return $product;
    }

    public function onShowOrder()
    {
        //TODO: need implementation or delet it
    }

    public function onCreateOrder()
    {
        //TODO: need implementation or delet it
    }
}

