<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager =$entityManager;
    }
    /**
     * @Route("/mot-de-passe-perdu", name="reset_password")
     */
    public function index(Request $request): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('home');
        }
        if($request->get('email')){
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if($user){
                //enregistrer en BDD la demande de changement de mot de passe
              $resetPassword = new ResetPassword();
              $resetPassword->setUser($user);
              $resetPassword->setToken(uniqid());
              $resetPassword->setCreatedAt(new \DateTime());
              $this->entityManager->persist($resetPassword);
              $this->entityManager->flush();
              return $this->redirectToRoute('update_password', [
                    'token' => $resetPassword->getToken()
                ]);
                //envoyer un mail pour la mise à jour du mot de passe
            } else {
                $this->addFlash('notification', 'cette adresse mail est inconnue');
            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/mot-de-passe-perdu/{token}", name="update_password")
     */
    public function update(Request $request, $token, UserPasswordEncoderInterface  $encoder)
    {
        $resetPassword = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        if (!$resetPassword){
            return $this->redirectToRoute('reset_password');
        }
        $now = new \DateTime();
         if ($now > $resetPassword->getCreatedAt()->modify('+ 1 hour')){
            $this->addFlash('notification', 'votre demande de mot de passe a expiré, merci de recommencer');
             return $this->redirectToRoute('reset_password');
         }
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('new_password')->getData();
            $user = $resetPassword->getUser();
            $password = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($password);
            $this->entityManager->flush();
             $this->addFlash('notification', 'Votre mot de passe a bien été mis à jour.');
               return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
