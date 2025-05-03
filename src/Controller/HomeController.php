<?php

namespace App\Controller;

use App\Repository\SweatshirtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur responsable de l'affichage de la page d'accueil.
 */
final class HomeController extends AbstractController
{
    /**
     * Affiche la page d'accueil avec les sweat-shirts en avant.
     * Cette méthode récupère les sweat-shirts qui sont marqués comme "en avant" dans la base de données
     * et les affiche sur la page d'accueil.
     *
     * @Route("/", name="app_home")
     *
     * @param SweatshirtRepository $sweatshirtRepository Le repository des sweat-shirts pour interroger la base de données.
     * @return Response La réponse HTTP qui contient la vue avec les sweat-shirts en avant.
     */
    #[Route('/', name: 'app_home')]
    public function index(SweatshirtRepository $sweatshirtRepository): Response
    {
        $featuredSweatshirts = $sweatshirtRepository->findBy(['featured' => true]);
        return $this->render('home/index.html.twig', [
            'featured_sweatshirts' => $featuredSweatshirts,
        ]);
    }
}
