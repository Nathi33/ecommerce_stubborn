<?php

namespace App\Controller;

use App\Entity\Sweatshirt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur responsable de l'affichage des détails d'un sweat-shirt.
 */
final class ProductController extends AbstractController
{
    /**
     * Affiche la fiche produit d'un sweat-shirt.
     *
     * Cette méthode récupère un sweat-shirt à partir de son identifiant
     * (grâce au ParamConverter de Symfony) et retourne une vue détaillée.
     *
     * @Route("/product/{id}", name="app_product")
     *
     * @param Sweatshirt $sweatshirt L'entité du sweat-shirt à afficher.
     * @return Response La vue contenant les détails du sweat-shirt.
     */
    #[Route('/product/{id}', name: 'app_product')]
    public function show(Sweatshirt $sweatshirt): Response
    {
        return $this->render('product/show.html.twig', [
            'sweatshirt' => $sweatshirt,
        ]);
    }
}
