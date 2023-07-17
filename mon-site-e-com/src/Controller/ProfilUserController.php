<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProfilUserController extends AbstractController
{
    #[Route('/profil/{id<\d+>}', name: 'profil_user', methods:['GET','POST'])]
    public function profil(User $user): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        if($this->getUser() != $user){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('profil_user/compte.html.twig');
    }
}
