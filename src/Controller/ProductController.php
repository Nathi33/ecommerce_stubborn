<?php

namespace App\Controller;

use App\Entity\Sweatshirt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product')]
    public function show(Sweatshirt $sweatshirt): Response
    {
        return $this->render('product/show.html.twig', [
            'sweatshirt' => $sweatshirt,
        ]);
    }
}
