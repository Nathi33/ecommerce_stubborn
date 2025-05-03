<?php

namespace App\Controller;

use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contrôleur responsable de la gestion des paiements via Stripe.
 */
class PaymentController extends AbstractController
{
    /**
     * Session de l'utilisateur, utilisée pour récupérer les éléments du panier.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private $session;

    /**
     * Constructeur du contrôleur de paiement.
     *
     * @param RequestStack $requestStack Pile de requêtes pour accéder à la session.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    /**
     * Crée une session de paiement Stripe à partir des éléments du panier.
     * Redirige vers la page de paiement Stripe si le panier contient des articles,
     * sinon redirige vers la page du panier avec un message d'avertissement.
     *
     * @Route("/create-checkout-session", name="app_create_checkout_session")
     *
     * @param StripeService $stripeService Service personnalisé pour la gestion de Stripe.
     * @return Response Redirection vers l'URL de paiement Stripe ou la page panier.
     */
    #[Route('/create-checkout-session', name: 'app_create_checkout_session')]
    public function checkout(StripeService $stripeService): Response
    {
        $cart = $this->session->get('cart', []);

        if (empty($cart)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart');
        }

        $lineItems = [];
        foreach ($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100, // prix en centimes
                ],
                'quantity' => 1,
            ];
        }

        $checkoutSession = $stripeService->createCheckoutSession(
            $lineItems,
            $this->generateUrl('app_payment_success', [], 0),
            $this->generateUrl('app_payment_cancel', [], 0)
        );

        return $this->redirect($checkoutSession->url);
    }

    /**
     * Affiche la page de confirmation après un paiement réussi.
     * Vide le panier de la session.
     *
     * @Route("/success", name="app_payment_success")
     *
     * @return Response La vue de confirmation du paiement.
     */
    #[Route('/success', name: 'app_payment_success')]
    public function success(): Response
    {
        $this->session->remove('cart');
        return $this->render('payment/success.html.twig');
    }

    /**
     * Affiche la page d’annulation de paiement si l’utilisateur quitte Stripe sans valider.
     *
     * @Route("/cancel", name="app_payment_cancel")
     *
     * @return Response La vue d’annulation du paiement.
     */
    #[Route('/cancel', name: 'app_payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}
