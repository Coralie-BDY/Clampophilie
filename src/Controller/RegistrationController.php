<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager =$entityManager;
    }

    /**
     * @Route("/inscription", name="registration")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $email_search = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if(!$email_search){
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $notification = "L'inscription s'est bien passée, vous pouvez vous connecter à votre compte";
                //envoi d'un mail pour confirmer l'inscription
            } else {
                $notification = "L'email utilisé a déjà un compte associé";

            }

        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
            'notification' =>$notification
        ]);
    }
}
