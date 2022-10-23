<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Repository\VoitureRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(VoitureRepository $voitureRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
         //on cree un formulaire de recherche sur les voitures 
       $rechercheVoiture = new RechercheVoiture(); 
       $form = $this->createForm(RechercheVoitureType::class, $rechercheVoiture);
       $form->handleRequest($request);
        // on cree une pagination avec le bundle knp_paginator
        $voitures = $paginatorInterface->paginate(
            $voitureRepository->findAllWithPagination($rechercheVoiture), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit par page*/
        );
        return $this->render('voiture/voitures.html.twig',[
            'voitures' => $voitures,
            "form" => $form->createView(),
            // si l'utilisateur est un admin on lui affiche la page admin
            "admin" => true,
        ]);        
    }

    //  on cree une route pour ajouter une voiture
    #[Route('/admin/voiture/ajouter', name: 'ajouter_voiture')]
    // on cree une route pour la page de modification d'une voiture
    #[Route('/admin/voiture/{id}', name: 'modif_voiture', methods: ['GET', 'POST'])]
    public function modification(Voiture $voiture = null, Request $request, ManagerRegistry $managerRegistry): Response
    {
        // si la voiture n'existe pas on la cree
        if(!$voiture){
            $voiture = new Voiture();
        }        
        // on cree un formulaire de modification de voiture
         $form = $this->createForm(VoitureType::class, $voiture);
         // on gere la requete
         $form->handleRequest($request);
            // si le formulaire est soumis et valide
            if($form->isSubmitted() && $form->isValid()){
                // on recupere le manager de doctrine
                $manager = $managerRegistry->getManager();
                // on persiste les donnees
                $manager->persist($voiture);
                // on enregistre les donnees
                $manager->flush();
                // on envoi un message flash
                $this->addFlash('success', "La voiture a bien été enregistrée");
                // on redirige vers la page de la voiture
                return $this->redirectToRoute('admin');
            }

        return $this->render('admin/modification.html.twig',[
           'voiture' => $voiture,
           "form" => $form->createView(),
       ]);
    }   
    

    // // on cree une route pour la page de suppression d'une voiture
    #[Route('/admin/voiture/{id}', name: 'voiture_delete', methods: ['SUP'])]
    public function delete(Voiture $voiture, Request $request, ManagerRegistry $managerRegistry): Response
    {
       
        if ($this->isCsrfTokenValid('SUP'.$voiture->getId(), $request->get('_token'))) 
            {
                $manager = $managerRegistry->getManager();
                $manager->remove($voiture);
                $manager->flush();
                $this->addFlash('success', "La voiture a bien été supprimée");
                // on redirige vers la page de la voiture
                return $this->redirectToRoute('admin');
                // return $this->render('voiture/delete.html.twig', [
                // 'voiture' => $voiture,
                // ]);
            }
    }   
}
