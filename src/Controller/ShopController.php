<?php

namespace App\Controller;

use App\Repository\SweatshirtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
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
