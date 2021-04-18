<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var UserInterface $user */
        $user = $this->getReference('jane_admin');

        $product = new Product();
        $product->setTitle('Test123');
        $product->setCreator($user);
        $manager->persist($product);
        $manager->flush();
    }
}