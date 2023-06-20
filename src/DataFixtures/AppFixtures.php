<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\Sex;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $kolebn = new User('Kolebn', 24, Sex::MALE);
         $timager = new User('Timager', 24, Sex::MALE);
         $nika = new User('Nika', 27, Sex::FEMALE);
         $manager->persist($kolebn);
         $manager->persist($timager);
         $manager->persist($nika);

        $manager->flush();
    }
}
