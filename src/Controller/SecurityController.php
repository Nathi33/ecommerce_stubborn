<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur de sécurité gérant l'authentification des utilisateurs.
 */
class SecurityController extends AbstractController
{
    /**
     * Affiche la page de connexion et gère les erreurs éventuelles de login.
     *
     * @param AuthenticationUtils $authenticationUtils Utilitaire pour récupérer les informations d'authentification
     *
     * @return Response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Route de déconnexion.
     *
     * Cette méthode ne sera jamais exécutée, car Symfony intercepte cette route
     * automatiquement via la configuration du pare-feu (`firewall.logout`).
     *
     * @return void
     *
     * @throws \LogicException
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion de votre pare-feu.');
    }
}
