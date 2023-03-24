<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Produits;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProduitsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
     // create 150 produits
     for ($i = 0; $i < 10; $i++) {
                  $category= $this->getReference('categoryShop-'. $faker->numberBetween(1,6));
                  $product = new Produits();
                  $product->setNom($faker->sentence);
                  $product->setSlug($faker->slug);
                  $product->setDescription($faker->text);
                  $product->setOnline(true);
                  $product->setPrix($faker->randomFloat(2));
                  $product->setCreatedAt(new DateTime('now'));
                  $product->setAttachement($faker->imageUrl(640, 480, 'animals', true));
                  $product->setCategory($category);
                  $manager->persist($product);
              }

        $manager->flush();
    }
}
?>