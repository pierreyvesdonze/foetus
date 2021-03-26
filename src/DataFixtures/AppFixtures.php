<?php

namespace App\DataFixtures;

use App\Entity\ImageEntity;
use App\Entity\RecipeCategory;
use App\Entity\RecipeMenu;
use App\Entity\Role;
use App\Entity\Tag;
use App\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager as PersistenceObjectManager;

class AppFixtures extends Fixture
{
    //php bin/console doctrine:fixtures:load
    public function load(PersistenceObjectManager $manager)
    {
        // Images
        for ($i = 0; $i < 21; $i++) {
            $image = new ImageEntity;
            $image->setPathName('assets/images/galerie/foetus-galerie-' . $i . '.png');
            $manager->persist($image);
        }

        $manager->flush();
    }
}
