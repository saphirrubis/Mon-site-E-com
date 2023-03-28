<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/Mon-panier', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig',['cart'=>$cartService->getTotal()]);
    }
    #[Route('/Mon-panier/add/{id<\d+>}', name: 'cart_add')]
    public function addToCart(CartService $cartService, Int $id): Response
    {
        $cartService->addToCart($id);
        return $this->redirectToRoute('cart_index');
    }
    #[Route('/Mon-panier/remove/{id<\d+>}', name: 'cart_remove')]
    public function removeToCart(CartService $cartService, Int $id): Response
    {
        $cartService->removeToCart($id);
        return $this->redirectToRoute('cart_index');
    }
    #[Route('/Mon-panier/decrease/{id<\d+>}', name: 'cart_decrease')]
    public function decrease(CartService $cartService, Int $id): RedirectResponse
    {
        $cartService->decrease($id);
        return $this->redirectToRoute('cart_index');
    }
    #[Route('/Mon-panier/removeAll', name: 'cart_removeAll')]
    public function removeAll(CartService $cartService): Response
    {
        $cartService->removeCartAll();
        return $this->redirectToRoute('shop_index');
    }
}
