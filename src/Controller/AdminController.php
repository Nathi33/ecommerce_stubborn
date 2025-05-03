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

/**
 * Contrôleur responsable de la gestion des opérations administratives sur les sweat-shirts.
 */
final class AdminController extends AbstractController
{
     /**
     * Affiche la liste des sweat-shirts et permet d'ajouter un nouveau sweat-shirt.
     * Cette méthode gère le formulaire d'ajout de sweat-shirt et le téléchargement de l'image.
     *
     * @Route("/admin", name="app_admin")
     *
     * @param Request $request La requête HTTP.
     * @param EntityManagerInterface $em L'interface pour gérer les entités Doctrine.
     * @param SluggerInterface $slugger Le service pour générer un slug à partir du nom de fichier.
     * @return Response La réponse contenant la vue du formulaire et la liste des sweat-shirts.
     */
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

    /**
     * Permet de modifier un sweat-shirt existant.
     * Cette méthode gère l'édition des données du sweat-shirt et le téléchargement d'une nouvelle image.
     *
     * @Route("/admin/edit/{id}", name="app_admin_edit")
     *
     * @param Sweatshirt $sweatshirt L'entité sweat-shirt à modifier.
     * @param Request $request La requête HTTP.
     * @param EntityManagerInterface $em L'interface pour gérer les entités Doctrine.
     * @param SluggerInterface $slugger Le service pour générer un slug à partir du nom de fichier.
     * @return Response La réponse contenant la vue avec le formulaire de modification.
     */
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

    /**
     * Permet de supprimer un sweat-shirt de la base de données.
     * Cette méthode gère la suppression d'un sweat-shirt spécifique.
     *
     * @Route("/admin/delete/{id}", name="app_admin_delete")
     *
     * @param Sweatshirt $sweatshirt L'entité sweat-shirt à supprimer.
     * @param EntityManagerInterface $em L'interface pour gérer les entités Doctrine.
     * @return Response La réponse redirigeant vers la page d'administration après la suppression.
     */
    #[Route('/admin/delete/{id}', name: 'app_admin_delete')]
    public function delete(Sweatshirt $sweatshirt, EntityManagerInterface $em): Response
    {
        $em->remove($sweatshirt);
        $em->flush();

        $this->addFlash('success', 'Le sweat-shirt a été supprimé avec succès.');
        return $this->redirectToRoute('app_admin');
    }
}
