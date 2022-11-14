<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class GlobalController extends AbstractController
{
    // route pour l'accueil
    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        return $this->render('global/accueil.html.twig');
    }
    // route pour l'inscription
    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request, ManagerRegistry $managerRegistry, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        // on affiche la page d'inscription
        $utilisateur = new Utilisateur();
        // on fait le lien entre le formulaire et l'entité
        $form = $this->createForm(InscriptionType::class, $utilisateur);
        // on recupère les données du formulaire par handleRequest
        $form->handleRequest($request);
       // on vérifie que le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
            // on encode le mot de passe
            $passwordCrypte = $userPasswordHasherInterface->hashPassword($utilisateur, $utilisateur->getPassword());
            // on affecte le mot de passe crypté à l'entité
            $utilisateur->setPassword($passwordCrypte);
            $utilisateur->setRoles('ROLE_USER');
            // on fait appel au manager de doctrine pour la persistance des données //
            $managerRegistry->getManager()->persist($utilisateur);
            // on fait appel au manager de doctrine pour l'envoi des données //
            $managerRegistry->getManager()->flush();
            // on affiche un message de confirmation
            $this->addFlash('success', 'Votre inscription a bien été prise en compte');
            // on redirige vers la page d'accueil
            return $this->redirectToRoute('accueil');
        }

        return $this->render('global/inscription.html.twig', [
            // on envoie le formulaire a la vue
            "form" => $form->createView(),
        ]);
    }
    
    // on crée une route pour la page de connexion
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        dump($username);
        return $this->render('global/login.html.twig', [
            'error' => $error,
            'username' => $username,
        ]);
    }

    // on crée une route pour la page de déconnexion
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
        // on ne met rien ici
    }
}
