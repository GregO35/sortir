<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Form\ExcursionType;
use App\Repository\ExcursionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExcursionController extends AbstractController
{
    /**
     * @Route("/accueil", name="excursion_list")
     */
    public function listExcursion()
    {
        return $this->render('user/accueil.html.twig', [
            'controller_name' => 'ExcursionController',
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
            $excursion->setOrganizer($this->getUser());
            $excursion->setState();

            $em = $this->getDoctrine()->getManager();
            $em->persist($excursion);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render("excursion/create.html.twig",[
            'ecursionForm' => $excursionForm->createView()
        ]);
    }
}
