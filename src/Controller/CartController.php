<?php

namespace App\Controller;

use App\Entity\Sweatshirt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    // Route pour ajouter un sweat au panier
    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function addToCart(Request $request, Sweatshirt $sweatshirt, SessionInterface $session): Response
    {
        // Récupère la taille choisie par l'utilisateur
        $size = $request->request->get('size');

        // Récupère le panier actuel s'il existe, sinon crée un tableau vide
        $cart = $session->get('cart', []);
        
        // Ajout du sweat au panier
        $cart[] = [
            'id' => $sweatshirt->getId(),
            'name' => $sweatshirt->getName(),
            'price' => $sweatshirt->getPrice(),
            'image' => $sweatshirt->getImage(),
            'size' => $size,
        ];

        // Enregistre le panier dans la session
        $session->set('cart', $cart);

        // Redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    // Route pour afficher le panier
    #[Route('/cart', name: 'app_cart')]
    public function cart(SessionInterface $session): Response
    {
        // Récupère les éléments du panier depuis la session
        $cart = $session->get('cart', []);

        // Calcule le total du panier
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'];
        }

        // Affiche le panier avec les articles et le total
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    // Route pour supprimer un sweat du panier
    #[Route('/cart/remove/{index}', name: 'app_cart_remove', methods: ['POST'])]
    public function removeFromCart(SessionInterface $session, int $index, Request $request): Response
    {
        // Verifie le CSRF token
        if (!$this->isCsrfTokenValid('remove-item-' .$index, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }
        // Récupère le panier actuel
        $cart = $session->get('cart', []);

        // Vérifie si l'index est valide
        if (isset($cart[$index])) {
            // Supprime l'article du panier
            unset($cart[$index]);
            // Réindexe le tableau
            $session->set('cart', array_values($cart));
        }

        // Redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }
}