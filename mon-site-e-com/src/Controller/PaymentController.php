<?php
namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Produits;
use App\Service\CartService;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
                  private  EntityManagerInterface $em;
                  private UrlGeneratorInterface $generator;

       public function __construct(EntityManagerInterface $em,UrlGeneratorInterface $generator )
                  {
                  $this->em = $em;
                  $this->generator = $generator;
                  }          
      #[Route('/order/create-session-stripe/{reference}', name:'payment_stripe' )]
      public function stripeCheckout($reference): RedirectResponse
      {
                  $produitstripe = [];
                  $order = $this->em->getRepository(Order::class)->findOneBy(['reference' => $reference ]);
                  if (!$order){
                   return $this->redirectToRoute('cart_index');
                  }
                  foreach($order->getRecapDetails()->getValues() as $produit){
                  $produitData= $this->em->getRepository(Produits::class)->findOneby(['nom'=>$produit->getProduit()]);
                  $produitstripe = [
                              'price_data'=> [
                                    'currency'=> 'eur',
                                    'unit_amount'=> $produitData->getPrix(),
                                    'product_data' => [
                                            'nom'=> $produit->getProduit(),         
                                    ]
                                    ],
                               'quantity' => $produit->getQuantity(),     
                                    ];
                                    $produitstripe = [
                                      'price_data'=> [
                                            'currency'=> 'eur',
                                            'unit_amount'=> $order->getTransporterPrix(),
                                            'product_data' => [
                                                    'nom'=> $order->getTransporterName(),         
                                            ]
                                            ],
                                       'quantity'=> 1 ,    
                                      ];
                
                  
              
                }                  
                Stripe::setApiKey('sk_test_51LkXkjJsbI4ynLiZzuiuKLYpZPLZ4SvvVXhbg09dF9Rwn4kdLGJE62xhu5G0Ik8ws8gU8FI3WG5vgm7cmCuq1I5D00V4Iy8lDt');
                  
                  $checkout_session = Session::create([
                    'customer_email' => "ets@gmail.com",
                    'payment_method_types'=> ['card'],                
                    'line_items' => [[
                        'price'=> $produitData->getPrix(),
                        
                        'quantity' => $produit->getQuantity() ]],
                    'mode' => 'payment',
                    'success_url' => $this->generator->generate('payment_success', 
                                    ['reference'=> $order->getReference()], 
                     UrlGeneratorInterface::ABSOLUTE_URL) ,
                    'cancel_url' => $this->generator->generate('payment_cancel', 
                                    ['reference'=> $order->getReference()],
                     UrlGeneratorInterface::ABSOLUTE_URL),
                    ]);
                    $order->setStripeSessionoId($checkout_session->id);
                    $this->em->flush();

                  return new RedirectResponse($checkout_session->url);

      }
      #[Route('/order/success/{reference}', name:'payment_success' )]
      public function stripeSuccess($reference, CartService $cartService): Response{
                  return $this->render('order/success.html.twig');
      }
      #[Route('/order/cancel/{reference}', name:'payment_cancel' )]
      public function stripeCancel($reference, CartService $cartService): Response{
                  return $this->render('order/cancel.html.twig');
      }
 
}  


?>