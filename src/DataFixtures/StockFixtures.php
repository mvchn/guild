<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StockFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var Product $product */
        $product = $this->getReference('product123a');

        $stock = new Stock();
        $stock->setProduct($product);
        $stock->setType('test');
        $stock->setAmount(0);
        $stock->setStartAt(new \DateTime());

        $manager->persist($stock);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['products'];
    }

}
