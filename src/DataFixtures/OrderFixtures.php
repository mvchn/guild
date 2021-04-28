<?php

namespace App\DataFixtures;

use App\Entity\Attribute;
use App\Entity\Order;
use App\Entity\OrderAttribute;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var Product $product */
        $product = $this->getReference('product123a');

        $order = new Order();
        $order->addProduct($product);
        $attributeName = (new Attribute())
            ->setName('name')
            ->setType(EmailType::class)
            ->setRequired(true)
            ->setLabel('Name')
            ->setVerify(false)
        ;
        $attributeEmail = (new Attribute())
            ->setName('email')
            ->setType(EmailType::class)
            ->setRequired(true)
            ->setLabel('Email')
            ->setVerify(true)
        ;

        $product->addAttribute($attributeName);
        $product->addAttribute($attributeEmail);

        $orderAttributeName = (new OrderAttribute())
                ->setAttribute($attributeName)
                ->setValue('test')
                //->setOrder($order) //TODO: must remove in next releases
        ;

        $orderAttributeEmail = (new OrderAttribute())
            ->setAttribute($attributeEmail)
            ->setValue('test@test.com')
            //->setOrder($order) //TODO: must remove in next releases
        ;

        $order->addOrderAttribute($orderAttributeName); //TODO: Must implement addAttribute in order
        $order->addOrderAttribute($orderAttributeEmail); //TODO: Must implement addAttribute in order
        $manager->persist($order);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
        ];
    }

}
