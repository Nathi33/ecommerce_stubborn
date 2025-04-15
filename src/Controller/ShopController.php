<?php

namespace App\Controller;

use App\Entity\Sweatshirt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(EntityManagerInterface $em): Response
    {
        $sweatshirts = $em->getRepository(Sweatshirt::class)->findAll();
        return $this->render('shop/index.html.twig', [
            'sweatshirts' => $sweatshirts,
        ]);
    }
}
