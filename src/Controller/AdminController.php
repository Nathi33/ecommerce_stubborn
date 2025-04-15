<?php

namespace App\Controller;

use App\Entity\Sweatshirt;
use App\Form\SweatshirtType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    // Ajout d'un sweat-shirts
    #[Route('/admin', name: 'app_admin')]
    public function index(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $sweatshirts = new Sweatshirt();
        $form = $this->createForm(SweatshirtType::class, $sweatshirts);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                }

                $sweatshirts->setImage($newFilename);
            }

            $em->persist($sweatshirts);
            $em->flush();

            $this->addFlash('success', 'Le sweat-shirt a été ajouté avec succès.');

            return $this->redirectToRoute('app_admin');
        }

        $sweatshirts = $em->getRepository(Sweatshirt::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'sweatshirts' => $sweatshirts,
        ]);
    }

    // Modification d'un sweat-shirt
    #[Route('/admin/edit/{id}', name: 'app_admin_edit')]
    public function edit(Sweatshirt $sweatshirt, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(SweatshirtType::class, $sweatshirt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                }

                $sweatshirt->setImage($newFilename);
            }

            $em->flush();

            $this->addFlash('success', 'Le sweat-shirt a été modifié avec succès.');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'sweatshirt' => $sweatshirt,
        ]);
    }

    // Suppression d'un sweat-shirt
    #[Route('/admin/delete/{id}', name: 'app_admin_delete')]
    public function delete(Sweatshirt $sweatshirt, EntityManagerInterface $em): Response
    {
        $em->remove($sweatshirt);
        $em->flush();

        $this->addFlash('success', 'Le sweat-shirt a été supprimé avec succès.');
        return $this->redirectToRoute('app_admin');
    }
}
