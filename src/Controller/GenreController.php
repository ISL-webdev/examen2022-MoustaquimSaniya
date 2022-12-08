<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    //afficher tous les genres ici, s'il n'y en a pas, retour à l'ajout de genres dans le form afin d'en ajouter en tout premier
    #[Route('/genre', name: 'app_genre')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Genre::class);
        $genres = $repository->findAll();

        if (!$genres){
            return $this->redirectToRoute('ajouter_genre');
        }

        return $this->render('genre/index.html.twig', [
            'controller_name' => 'GenreController',
            'genres' => $genres,
        ]);
    }

    //formulaire ajout de genre
    #[Route('/genre/ajouter', name:'ajouter_genre')]
    public function ajouterGenre(Request $request, EntityManagerInterface $entityManager){
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $genre = $form->getData();
            $entityManager->persist($genre);
            $entityManager->flush();

            return $this->redirectToRoute('app_genre');

        }

        return $this->renderForm('genre/ajouterGenre.html.twig', [
            'form'=>$form,
        ]);


    }

    //modifier une catégorie
    #[Route('genre/{id}/modifier', name:'modifier_genre')]
    public function modifierGenre(Request $request, EntityManagerInterface $entityManager, int $id){
        $repository = $entityManager->getRepository(Genre::class);
        $genre = $repository->find($id);
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $genre = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_genre');
        }

        return $this->renderForm('genre/modifierGenre.html.twig', [
            'form'=>$form,
            'genre' =>$genre,
        ]);

    }


}
