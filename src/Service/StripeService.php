<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\Checkout\Session;

/**
 * Service pour interagir avec l'API Stripe, en particulier pour créer une session de paiement.
 */
class StripeService
{
    /**
     * Constructeur du service, initialise la clé secrète Stripe pour l'authentification.
     * 
     * @param string $stripeSecretKey La clé secrète de l'API Stripe pour effectuer les requêtes.
     */
    public function __construct(private string $stripeSecretKey)
    {
        // Définition de la clé API secrète de Stripe
        Stripe::setApiKey($this->stripeSecretKey);
    }

    /**
     * Crée une session de paiement avec Stripe Checkout.
     * 
     * Cette méthode génère une session de paiement que l'utilisateur peut utiliser
     * pour compléter son achat en ligne. Elle inclut les éléments du panier,
     * les URLs de succès et d'annulation.
     * 
     * @param array $lineItems Les éléments du panier pour le paiement (par exemple, articles, prix).
     * @param string $successUrl L'URL où l'utilisateur sera redirigé après un paiement réussi.
     * @param string $cancelUrl L'URL où l'utilisateur sera redirigé si le paiement est annulé.
     * 
     * @return Session La session de paiement Stripe créée.
     */
    public function createCheckoutSession(array $lineItems, string $successUrl, string $cancelUrl)
    {
        // Création de la session Stripe Checkout avec les informations fournies
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
    }
}
