<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture implements FixtureGroupInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFullName('test');
        $user->setUsername('test@guild.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'test'));
        $user->setEmail('test@guild.com');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $product = new Product();
        $product->setTitle('product123a');
        $product->setCreator($user);
        $manager->persist($product);

        $this->addReference($product->getTitle(), $product);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['products'];
    }
}
