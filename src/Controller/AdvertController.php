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
    /**
     * Cette fonction nous permet d'afficher les annonces qui sont enregistrées dans notre base de données
     *
     * @param AdvertRepository $advertRepository
     * @return Response
     */
    #[Route('/annonces', name: 'app_advert')]
    public function index(AdvertRepository $advertRepository): Response
    {
        return $this->render('advert/index.html.twig', [

            'adverts' => $advertRepository->findBy([], ['createdAt' => 'desc'])
        ]);
    }


    /**
     * Cette fonction nous permet d'afficher uniquement une annonce grâce à son slug
     */
    #[Route('/annonces/{slug}', name:'advert_show')]
    public function show(AdvertRepository $advertRepository, $slug) {

        $advert = $advertRepository->findOneBy([

            'slug' => $slug
        ]);

         if (!$advert) {
            
            return $this->redirectToRoute('app_ads');
         }

        return $this->render('advert/show.html.twig', [

            'advert' => $advert
        ]);
    }

    /**
     * Cette fonction nous permet de créer une annonce
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param SluggerInterface $slugger
     * @return Response
     */
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
