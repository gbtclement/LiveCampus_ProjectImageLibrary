<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImageFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $image = new Image();
            $image->setName("Image $i");
            $image->setUrl("https://example.com/image$i.jpg");

            $manager->persist($image);
        }

        $manager->flush();
    }
}
