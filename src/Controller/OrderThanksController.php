<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderThanksController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_thanks")
     */
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()){
            $this->redirectToRoute('home');
        }
        if(!$order->getState() == 0){
            $cart->remove();
            $order->setState(1);
            $this->entityManager->flush();

            //envoi de l'email pour confirmer la commande

        }

        return $this->render('order_thanks/index.html.twig', [
            'order' =>$order
        ]);

    }
}
