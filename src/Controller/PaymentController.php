<?php

namespace App\Controller;

use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends AbstractController
{
    private $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

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

    #[Route('/success', name: 'app_payment_success')]
    public function success(): Response
    {
        $this->session->remove('cart');
        return $this->render('payment/success.html.twig');
    }

    #[Route('/cancel', name: 'app_payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}
