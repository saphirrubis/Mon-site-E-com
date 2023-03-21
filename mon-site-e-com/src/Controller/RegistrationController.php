<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\MailerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, 
    EntityManagerInterface $entityManager, MailerService $mailerService, TokenGeneratorInterface $tokenGeneratorInterface): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            //Token
            $tokenRegistration = $tokenGeneratorInterface->generateToken();
            //User
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //User Token
            $user->setTokenRegistration($tokenRegistration);

            $entityManager->persist($user);
            $entityManager->flush();

            //Mailer Send
            $mailerService->send(
                $user->getEmail(),
                'confirmation du compte utilisateur',
                'registration_confirmation.html.twig',
                [
                    'user'=> $user,
                    'token'=> $tokenRegistration,
                    'lifeTimeToken'=> $user->getTokenRegistrationLifeTime()->format('d-m-Y-H-i-s')
                ]
            );

            // do anything else you need here, like send an email
            $this->addFlash('success', 'votre compte à bien été créé, veuillez véfifier vos email pour l\'activer');

            return $this->redirectToRoute('app_login');
            // return $userAuthenticator->authenticateUser(
            //     $user,
            //     $authenticator,
            //     $request
            // );
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/verify/{token}/{id<\d+>}', name: 'account_verify', methods:['GET'])]
    public function verify (string $token, User $user, EntityManagerInterface $em):Response{
        if($user->getTokenRegistration() !==$token){
            throw new AccessDeniedException();
        }
        if ($user->getTokenRegistration()=== null){
            throw new AccessDeniedException();
        }
        if(new DateTime('now')> $user->getTokenRegistrationLifeTime()){
            throw new AccessDeniedException();
        }
        $user->setIsverified(true);
        $user->setTokenRegistration(null);
        $em->flush();

        $this->addFlash('success','Votre compte a bien été activé, vous pouvez maintenant vous connecter.');
        return $this->redirectToRoute(('app_login'));
    }
}