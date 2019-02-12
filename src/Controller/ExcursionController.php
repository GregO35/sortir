<?php

namespace App\Controller;

use App\Entity\Excursion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExcursionController extends AbstractController
{

    /**
     * @Route("/sortir/accueil", name="accueil", methods={"GET"})
     */
    //requête pour afficher toutes les sorties (pour tester!) et les afficher (pour tester aussi!)
    public function listExcursion()
    {
        $excursionRepository = $this
            ->getDoctrine()
            ->getRepository(Excursion::class);

        $excursion = $excursionRepository->findAll();
        //dd($excursion);
        return $this->render('default/index.html.twig', [
            "excursions"=>$excursion
        ]);
    }
}
