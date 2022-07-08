<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertFormType;
use App\Repository\AdvertRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdvertController extends AbstractController
{
    #[Route('/annonces', name: 'app_advert')]
    public function index(AdvertRepository $advertRepository): Response
    {
        return $this->render('advert/index.html.twig', [

            'adverts' => $advertRepository->findBy([], ['createdAt' => 'desc'])
        ]);
    }




    #[Route('/deposer-une-annonce', name:'create_advert')]
    public function create(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {   

        $entityManager = $doctrine->getManager();

        $advert = new Advert;

        $form = $this->createForm(AdvertFormType::class, $advert);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $advert->setUser($this->getUser());
            $advert->setSlug(strtolower($slugger->slug($advert->getTitle())));
            
            $entityManager->persist($advert);
            $entityManager->flush();
            //  $flasher->addSuccess('Bravo ! Votre annonce a été déposée.');
            // return $this->redirectToRoute("app_ads");
        }
        return $this->render('advert/create.html.twig', [
            "advertForm" => $form->createView()
        ]);
    }
}
