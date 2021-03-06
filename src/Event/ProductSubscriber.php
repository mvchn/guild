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
            AttributeEvent::NEW => 'onCreateAttribute',
        ];
    }

    public function onStoreProduct(ProductEvent $event): Product
    {
        $product = $event->getProduct();

        $product->setCreator($this->security->getUser());

        return $product;
    }

    public function onCreateAttribute(AttributeEvent $event)
    {
        $attribute = $event->getAttribute();

        $attribute->setVerify(false);

        if('email' === $attribute->getName()) {
            $attribute->setVerify(true);
        }
    }

    public function onShowOrder()
    {
        //TODO: need implementation or delet it
    }

    public function onCreateOrder(ProductEvent $event)
    {
        //TODO: need implementation or delet it
    }

}

