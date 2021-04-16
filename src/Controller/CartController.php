<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/panier", name="cart")
     */
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' =>  $cart->getFull()
        ]);
    }

    /**
     * @Route("/panier/ajout/{id}", name="add_to_cart")
     */
    public function add(Cart $cart, $id)
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/panier/retirer", name="remove_cart")
     */
    public function remove(Cart $cart)
    {
        $cart->remove();
        return $this->redirectToRoute('products');
    }
    /**
     * @Route("/panier/supprimer/{id}", name="delete_cart")
     */
    public function delete(Cart $cart, $id)
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/panier/diminuer/{id}", name="decrease_cart")
     */
    public function decrease(Cart $cart, $id)
    {
        $cart->decrease($id);
        return $this->redirectToRoute('cart');
    }


}
