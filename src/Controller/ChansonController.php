<?php

namespace App\Controller;

use App\Entity\Chanson;
use App\Form\ChansonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChansonController extends AbstractController
{
    //afficher toutes les chansons ici, s'il n'y en a pas, retour à l'ajout de chansons dans le form afin d'en ajouter en tout premier
    #[Route('/chanson', name: 'app_chanson')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Chanson::class);
        $chansons = $repository->findAll();

        if (!$chansons){
            return $this->redirectToRoute('ajouter_chanson');
        }
        return $this->render('chanson/index.html.twig', [
            'controller_name' => 'ChansonController',
            'chansons' => $chansons,
        ]);
    }

    //formulaire ajout de chansons
    #[Route('/chanson/ajouter', name:'ajouter_chanson')]
    public function ajouterChanson(Request $request, EntityManagerInterface $entityManager){
        $chanson = new Chanson();
        $chanson->setDateAjout(new \DateTime('now'));
        $form = $this->createForm(ChansonType::class, $chanson);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $chanson = $form->getData();
            $entityManager->persist($chanson);
            $entityManager->flush();

            return $this->redirectToRoute('app_chanson');

        }

        return $this->renderForm('chanson/ajouterChanson.html.twig', [
            'form'=>$form,
        ]);
    }

    //affichage une chanson
    #[Route('/chanson/{id}', name:'afficher_chanson')]
    public function afficherChanson(int $id, EntityManagerInterface $entityManager){
        $repository = $entityManager->getRepository(Chanson::class);
        $chanson = $repository->find($id);

        return $this->render('chanson/afficherChanson.html.twig', [
            'chanson' => $chanson,
        ]);

    }

    //suppression de chanson
    #[Route('/chanson/{id}/supprimer', name:'supprimer_chanson')]
    public function supprimerChanson(int $id, EntityManagerInterface $entityManager){
        $repository = $entityManager->getRepository(Chanson::class);
        $chanson = $repository->find($id);
        $entityManager->remove($chanson);
        $entityManager->flush();

        return $this->redirectToRoute('app_chanson');

    }

    //modification de chanson je ne sais pas comment updater uniquement les 3 champs demandés
    // à cause de ma condition isSubmitted et isValid, ça ne marche pas quand je met uniquement les 3 champs dans ma vue
    // je dois rendre tous les champs du form
    #[Route('/chanson/{id}/modifier', name : 'modifier_chanson')]
    public function modifierChanson(int $id, EntityManagerInterface $entityManager, Request $request){
        $repository = $entityManager->getRepository(Chanson::class);
        $chanson = $repository->find($id);
        $form = $this->createForm(ChansonType::class, $chanson);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $chanson = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_chanson');
        }

        return $this->renderForm('chanson/modifierChanson.html.twig', [
            'form'=>$form,
            'chanson' =>$chanson,
        ]);


    }


}
