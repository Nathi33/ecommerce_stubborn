<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Authenticator personnalisé pour la connexion des utilisateurs.
 * Étend AbstractLoginFormAuthenticator pour gérer le processus d'authentification via un formulaire.
 */
class AuthAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    // Nom de la route utilisée pour afficher le formulaire de connexion
    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * Crée le Passport utilisé pour authentifier un utilisateur.
     *
     * Ce Passport contient :
     * - le badge UserBadge avec le nom d'utilisateur
     * - les credentials de mot de passe
     * - un badge CSRF pour vérifier le token
     * - un badge RememberMe pour activer l'option "se souvenir de moi"
     *
     * @param Request $request
     * @return Passport
     */
    public function authenticate(Request $request): Passport
    {
        $username = $request->getPayload()->getString('username');

        // Sauvegarde du dernier nom d'utilisateur pour pré-remplir le champ en cas d'erreur
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    /**
     * Gère la redirection après une authentification réussie.
     *
     * Redirige vers la page initialement demandée (targetPath), si elle existe,
     * sinon vers la page d’accueil.
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Récupérer le targetPath à partir de la session
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        // Debugging
        dump($targetPath);
        
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        // Redirection par défaut après connexion
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    /**
     * Retourne l'URL de la page de connexion.
     *
     * Utilisé pour rediriger l’utilisateur si l’authentification échoue.
     *
     * @param Request $request
     * @return string
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
