<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AuthAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface; 
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    //private EmailVerifier $emailVerifier;
    private UserAuthenticatorInterface $userAuthenticator;
    private AuthAuthenticator $authenticator;

    public function __construct(UserAuthenticatorInterface $userAuthenticator, AuthAuthenticator $authenticator)
    {
        //$this->emailVerifier = $emailVerifier;
        $this->userAuthenticator = $userAuthenticator;
        $this->authenticator= $authenticator;
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager, 
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mailer
    ): Response
    {
        $user = new User();

        // Formulaire lié à l'entité User
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération et vérification du mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            $plainPasswordConfirm = $form->get('plainPasswordConfirm')->getData();
            // Vérification que les mots de passe correspondent
            if ($plainPassword !== $plainPasswordConfirm) {
                $form->get('plainPasswordConfirm')->addError(new \Symfony\Component\Form\FormError('Les mots de passe ne correspondent pas.'));
            } else {
                // Hashage du mot de passe
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                // Enregistrement de l'utilisateur dans la base de données
                $entityManager->persist($user);
                $entityManager->flush();

                // Génération d'un token de vérification
                $token = $tokenGenerator->generateToken();
                $signature = hash('sha256', $user->getId() . $token);

                // Génération du lien
                $signedUrl = $this->generateUrl('app_verify_email', [
                    'id' => $user->getId(),
                    'token' => $token,
                    'signature' => $signature,
                    'expires' => time() + 3600,                 
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                // Envoi de l'email de confirmation
                $email = (new TemplatedEmail())
                    ->from(new Address('stubborn.blabla@gmail.com', 'Stubborn'))
                    ->to($user->getEmail())
                    ->subject('Lien de confirmation d\'inscription')
                    ->htmlTemplate('emails/register.html.twig')
                    ->context([
                        'user' => $user,
                        'signedUrl' => $signedUrl,
                    ]);
                
                $mailer->send($email);
                
                return $this->render('registration/check_email.html.twig', [
                    'email' => $user->getEmail(),
                ]);
            }      
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email/{id}/{token}/{signature}/{expires}', name: 'app_verify_email')]
    public function verifyUserEmail(
        int $id,
        string $token,
        string $signature,
        int $expires,
        Request $request, 
        TranslatorInterface $translator, 
        EntityManagerInterface $entityManager,
    ): Response{
        // récupérer l'utilisateeur par son ID
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            $this->addFlash('verify_email_error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('app_register');
        }

        // Vérification de l'expiration du lien
        if ($expires < time()) {
            $this->addFlash('verify_email_error', 'Le lien de vérification a expiré.');
            return $this->redirectToRoute('app_register');
        }

        // Vérification de la signature
        $expectedSignature = hash('sha256', $user->getId() . $token);
        if ($signature !== $expectedSignature) {
            $this->addFlash('verify_email_error', 'Le lien de vérification est invalide.');
            return $this->redirectToRoute('app_register');
        }

        // Validation de l'email
        $user->setIsVerified(true);
        $entityManager->flush();

        // Authentifier l'utilisateur après la vérification de l'email
        $this->addFlash('success', 'Votre adresse mail a été vérifiée avec succès.');

        // Authentification automatique après vérification
        return $this->userAuthenticator->authenticateUser(
            $user,
            $this->authenticator,
            $request
        );

        // Redirection après authentification
        return $this->redirectToRoute('app_home');
    }
}
