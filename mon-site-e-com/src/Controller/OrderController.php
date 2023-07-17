<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Address;
use App\Entity\RecapDetails;
use App\Form\OrderType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
        private  EntityManagerInterface $em;

        public function __construct(EntityManagerInterface $em)
        {
            $this->em =$em;
        }

    #[Route('/order', name: 'order_create')]
    public function index(CartService $cartService): Response
    {
        if(!$this->getUser()){
                return $this->redirectToRoute('app_login');
        }
        $form =$this->createForm(OrderType::class, null, ['user'=> $this->getUser()]);
        
        
        return $this->render('order/index.html.twig', 
            ['form' => $form->createView(),
            'recapCart' => $cartService->getTotal(),
        ]);
    }
    #[Route('/verify', name: 'order_prepare', methods:['POST'])]
    public function prepareOrder(CartService $cartService, Request $request): Response
    {
            if (!$this->getUser()){
                return $this->redirectToRoute('app_login');
            }
        $form =$this->createForm(OrderType::class, null, ['user'=> $this->getUser()]);
        $form->handleRequest($request);
        if ($form-> isSubmitted() && $form-> isValid()){
            $datetime = new \DateTime('now');
            $transporter = $form->get('transporter')->getData();
            $delivery = $form->get('addresses')->getData();
            $deliveryForOrder = $delivery->getFirstName().' '.$delivery->getLastName();
            $deliveryForOrder.= '</br>'. $delivery->getPhone();
            if($delivery->getCompany()){
                $deliveryForOrder.= ' -'. $delivery->getcompany();
            }
            $deliveryForOrder.= '</br>'. $delivery->getAdress();
            $deliveryForOrder.= '</br>'. $delivery->getPostalCode().'-'. $delivery->getCity();
            $deliveryForOrder.= '</br>'. $delivery->getCountry();
            $order = new Order();
            $reference =$datetime->format('dmy').'-'.uniqid() ;
            $order->setUSer($this->getUser());
            $order->setReference($reference);
            $order->setCreatedAt($datetime);
            $order->setDelivery($deliveryForOrder);
            $order->setTransporterName($transporter->getNom());
            $order->setTransporterPrix($transporter->getPrix());
            $order->setIsPaid(0);
            $order->setMethod('stripe');

            $this->em->persist($order);
            foreach ($cartService->getTotal() as $produit)
            {
                $recapDetails= new RecapDetails();
                $recapDetails->setOrderProduit($order);
                $recapDetails->setQuantity($produit['quantity']);
                $recapDetails->setPrix($produit['produit']->getprix());
                $recapDetails->setProduit($produit['produit']->getNom());
                $recapDetails->setTotalRecap($produit['produit']->getPrix()* $produit['quantity']);

                $this->em->persist($recapDetails);
            }
           $this->em->flush();
           return $this-> render('order/recap.html.twig', [
                    'method'=> $order->getMethod(),
                    'recapCart' =>$cartService->getTotal(),
                    'transporter' => $transporter,
                    'delivery'=> $deliveryForOrder,
                    'reference'=> $order->getReference(),
           ]);
        }

        return $this-> redirectToRoute('cart_index');
    }
}
