<?php

namespace App\DataFixtures;

use App\Entity\Pokemon;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // PWD = test
        $pwd = '$2y$13$x0DMYXLweCoUQeYu6C7GVeL9P4U6dZvIgm35CRQxPCe.3l3xKxEjm';

        $object = (new User())
            ->setEmail('user@user.fr')
            ->setRoles([])
            ->setPassword($pwd)
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('creator@user.fr')
            ->setRoles(['ROLE_CREATOR'])
            ->setPassword($pwd)
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('admin@user.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($pwd)
        ;
        $manager->persist($object);

        $manager->flush();
    }
}
