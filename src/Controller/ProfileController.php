<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }


    #[Route('/edit_profile', name: 'edit_profile')]
    public function editProfile(Request $request, ManagerRegistry $doctrine, NotyfFactory $flasher): Response
    {   
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $entityManager->persist($user);
            $entityManager->flush();
            $flasher->addSuccess('Votre profil a bien été mis à jour');
            return $this->redirectToRoute('app_profile');
        }
        return $this->render('profile/edit.html.twig', [

            'ProfileForm' => $form->createView()
        ]);
    }
}
