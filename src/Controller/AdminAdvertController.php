<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertFormType;
use App\Repository\AdvertRepository;
use Flasher\Notyf\Prime\NotyfFactory;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminAdvertController extends AbstractController
{   

    /**
     * Afficher la liste des annonces appartenant à un utilisateur
     */
    #[Route('/vos-annonces', name: 'admin_advert')]
    public function index(): Response
    {
        return $this->render('admin_advert/index.html.twig');
    }



    /**
     * Fonction permettant d'editer une annonce
     */
    #[Route('/annonces/edit/{id}', name:"edit_advert")]
    public function edit($id, AdvertRepository $advertRepository, Request $request, ManagerRegistry $doctrine, NotyfFactory $flasher) {

      $entityManager = $doctrine->getManager();
      $advert = $advertRepository->find($id);
      $form = $this->createForm(AdvertFormType::class, $advert);
      
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          
         $entityManager->persist($advert);
         $entityManager->flush();
         /**
          * On envoie une notification à l'utilisateur que la modification à été un succés
          */
         $flasher->addSuccess('Votre annonce à été modifié avec succéss !');
        /**
         * On le redirige vers la page des annonces
         */
          return $this->redirectToRoute('app_advert');
      }
      return $this->render('admin_advert/edit.html.twig', [

          'advert' => $advert,
          'advertForm' => $form->createView()
      ]);

    }

    /**
     * Fonction permettant de supprimer une annonce
     */

     #[Route('/annonces/delete/{id}', name:"delete_advert")]
    public function delete($id, ManagerRegistry $doctrine, NotyfFactory $flasher):Response 
    {

        $em = $doctrine->getManager();
        $adRepository = $em->getRepository(Advert::class);
        $ad = $adRepository->find($id);
        $em->remove($ad);
        $em->flush();
        $flasher->addSuccess('Votre annonce à été supprimé avec succés !');
        return $this->redirectToRoute('admin_advert');
    

    }
}
