<?php

namespace App\Controller;

use App\Entity\State;
use App\Entity\Excursion;
use App\Entity\User;
use App\Form\ExcursionType;
use App\Repository\ExcursionRepository;
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

    /**
     * @Route("/sortir/sortie/inscription/{id}",
     *     name="excursion_register",
     *     requirements={"id":"\d+"},
     *     )
     */
    public function registerExcursion($id){

        $excursionRepository = $this->getDoctrine()->getRepository(Excursion::class);
        $excursion = $excursionRepository->find($id);

        $endDate = $excursion->getEndDate();
        $actualDate = date("d-m-Y H:i:s");
        $actualDate = date_create($actualDate);

        if(date_diff($endDate,$actualDate)->invert &&
            $excursion->getRegistrationNumberMax() > $excursion->getRegisterExcursion()->count())
        {
            $user = $this->getUser();
            $user->addExcursion($excursion);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/sortir/sortie/désinscription/{id}",
     *      name="excursion_unregister",
     *      requirements={"id":"\d+"}
     *     )
     */
    public function unregisterExcursion($id){
        $excursionRepository = $this->getDoctrine()->getRepository(Excursion::class);
        $excursion = $excursionRepository->find($id);
        $user = $this->getUser();

        $endDate = $excursion->getEndDate();
        $actualDate = date("d-m-Y H:i:s");
        $actualDate = date_create($actualDate);

        if(date_diff($endDate,$actualDate)->invert)
        {
            $user->removeExcursion($excursion);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/sortir/sortie/annulation/{id}",
     *      name="excursion_delete",
     *      requirements={"id":"\d+"}
     *     )
     */
    public function deleteExcursion($id){
        $excursionRepository = $this->getDoctrine()->getRepository(Excursion::class);
        $excursion = $excursionRepository->find($id);

        $endDate = $excursion->getEndDate();
        $actualDate = date("d-m-Y H:i:s");
        $actualDate = date_create($actualDate);

        if(date_diff($endDate,$actualDate)->invert)
        {
            foreach($excursion->getRegisterExcursion() as $register){
                $excursion->removeRegisterExcursion($register);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($excursion);
            $em->flush();

            $excursionRepository->remove($id);
        }

        return $this->redirectToRoute('index');
    }


    /**
     * @Route("/sortir/sortie/afficher/{id}",
     *      name="excursion_details",
     *      requirements={"id":"\d+"},
     *      methods={"GET","POST"})
     */
    public function excursionDetails($id, Request $request){
        $excursionRepository= $this->getDoctrine()->getRepository(Excursion::class);
        $excursion= $excursionRepository->find($id);
        //dd($excursion);
        return $this->render("excursion/details.html.twig",[
            'excursion' => $excursion
        ]);
    }
}
