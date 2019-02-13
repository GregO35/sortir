<?php

namespace App\Controller;

use App\Entity\Excursion;

use App\Entity\State;
use App\Form\ExcursionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExcursionController extends AbstractController
{

    /**
     * @Route("/sortir/accueil", name="index", methods={"GET"})
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

    /**
     * @Route("/sortir/sortie/creer", name="excursion_add")
     */
    public function addExcursion(Request $request)
    {
        $excursion = new Excursion();
        $excursionForm = $this->createForm(ExcursionType::class, $excursion);
        $excursionForm->handleRequest($request);

        if($excursionForm->isSubmitted() && $excursionForm->isValid())
        {
            $stateRepository = $this->getDoctrine()->getRepository(State::class);
            $stateInitial = $stateRepository->find(3);

            $excursion->setOrganizer($this->getUser());
            $excursion->setState($stateInitial);

            $em = $this->getDoctrine()->getManager();
            $em->persist($excursion);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render("excursion/create.html.twig",[
            'excursionForm' => $excursionForm->createView()
        ]);
    }
}
