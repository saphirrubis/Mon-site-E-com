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
                  1=>['name'=>'Vétement','slug'=>'Vétement'],
              
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