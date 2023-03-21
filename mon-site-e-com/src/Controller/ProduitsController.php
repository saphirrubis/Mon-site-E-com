<?php

namespace App\Controller;

use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitsController extends AbstractController
{
    #[Route('/boutique', name: 'shop_index')]
    public function __construct( private readonly EntityManagerInterface $em){}
    public function index(): Response
    {
        $prod = $this->em->getRepository(Produits::class)->findAll();

        return $this->render('produits/index.html.twig',['prod'=> $prod ]);

    }
}
