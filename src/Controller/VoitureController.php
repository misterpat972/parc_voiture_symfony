<?php

namespace App\Controller;

use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoitureController extends AbstractController
{
    #[Route('/client/voitures', name: 'voitures')]
    // on récupère la liste des voitures dans la base de données avec le repository
    // on ajoute paginatorInterface pour la pagination
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
            "form" => $form->createView()
        ]);        
    }
}
