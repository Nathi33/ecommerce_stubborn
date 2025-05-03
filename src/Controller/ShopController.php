<?php

namespace App\Controller;

use App\Repository\SweatshirtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur de la boutique affichant la liste des sweatshirts.
 */
final class ShopController extends AbstractController
{
    /**
     * Affiche la liste des sweatshirts disponibles.
     *
     * Cette méthode peut filtrer les produits par plage de prix via un paramètre GET `priceRange` (ex: `20-50`).
     *
     * @param SweatshirtRepository $sweatshirtRepository Le repository pour accéder aux sweatshirts
     * @param Request $request La requête HTTP contenant éventuellement le paramètre de filtre
     *
     * @return Response La réponse contenant le rendu de la page boutique
     */
    #[Route('/products', name: 'app_shop')]
    public function index(SweatshirtRepository $sweatshirtRepository, Request $request): Response
    {
        //$sweatshirts = $sweatshirtRepository->findAll();
        $priceRange = $request->query->get('priceRange');
        $sweatshirts = [];

        if ($priceRange) {
            [$min, $max] = explode('-', $priceRange);
            $sweatshirts = $sweatshirtRepository->createQueryBuilder('s')
                ->where('s.price >= :min')
                ->andWhere('s.price <= :max')
                ->setParameter('min', $min)
                ->setParameter('max', $max)
                ->getQuery()
                ->getResult();
        } else {
            $sweatshirts = $sweatshirtRepository->findAll();
        }
        return $this->render('shop/index.html.twig', [
            'sweatshirts' => $sweatshirts,
        ]);
    }
}
