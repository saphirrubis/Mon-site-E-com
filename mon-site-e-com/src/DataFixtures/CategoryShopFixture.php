<?php

namespace App\DataFixtures;

use App\Entity\CategoryShop;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryShopFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
      $c=[
                  1=>['name'=>'Homme','slug'=>'Homme'],
                  2=>['name'=>'Femme','slug'=>'Femme'],
                  3=>['name'=>'Enfant','slug'=>'Enfant'],
                  4=>['name'=>'Maison','slug'=>'Maison'],
                  5=>['name'=>'Accesoires','slug'=>'Accesoires'],
                  6=>['name'=>'Animaux','slug'=>'Animaux'],
                  7=>['name'=>'Autres','slug'=>'Autres'],
      ];
      foreach($c as $k =>$value){
                  $categoryShop = new CategoryShop();
                  $categoryShop->setName($value['name']);
                  $categoryShop->setSlug($value['slug']);
                  $manager->persist($categoryShop);
                  $this->addReference('categoryShop-'.$k , $categoryShop);
      }

        $manager->flush();
    }
}
?>