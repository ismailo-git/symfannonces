<?php

namespace App\Controller;

use App\Entity\Advert;
use Flasher\Notyf\Prime\NotyfFactory;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdvertController extends AbstractController
{
    #[Route('/vos-annonces', name: 'admin_advert')]
    public function index(): Response
    {
        return $this->render('admin_advert/index.html.twig');
    }

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
