<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\State;
use App\Entity\Excursion;
use App\Form\CityType;
use App\Form\ExcursionType;
use App\Form\PlaceType;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExcursionController extends AbstractController
{

    /**
     * @Route(
     *     "/sortir/accueil",
     *     name="index",
     *     methods={"GET","POST"}
     *     )
     */
    //requête pour afficher toutes les sorties (pour tester!) et les afficher (pour tester aussi!)
    public function listExcursion()
    {
        $excursionRepository = $this->getDoctrine()->getRepository(Excursion::class);
        $siteRepository = $this->getDoctrine()->getRepository(Site::class);

        $sites = $siteRepository->findAll();

        if($_POST)
        {

            $site = $_POST["sell"];
            $name = $_POST["name"];
            $dateStart = $_POST["date_start"];
            $dateEnd = $_POST["date_end"];
            $organizer = false;
            $register = false;
            $notRegister = false;
            $passedExcursion = false;

            if(isset($_POST["organizer"]))
            {
                $organizer = true;
            }

            if(isset($_POST["register"]))
            {
                $register = true;
            }

            if(isset($_POST["not_register"]))
            {
                $notRegister = true;
            }

            if(isset($_POST["passed_excursion"]))
            {
                $passedExcursion = true;
            }

            $siteRepository = $this->getDoctrine()->getRepository(Site::class);

            $excursions = $excursionRepository->findAllByFilters($siteRepository, $site, $name, $dateStart, $dateEnd,
                                    $organizer, $register, $notRegister, $passedExcursion, $this->getUser());

        }
        else
        {
            $excursions = $excursionRepository->findAll();
        }

        return $this->render('default/index.html.twig', [
            "excursions"=>$excursions,
            'sites' => $sites
        ]);
    }

    /**
     * @Route(
     *     "/sortir/sortie/creer",
     *     name="excursion_add",
     *     methods={"GET","POST"}
     *     )
     */
    public function addExcursion(Request $request)
    {
        $user = $this->getUser();

        //Création du formulaire d'excursion
        $excursion = new Excursion();

        $dateTime = date_create();
        $excursion->setStartDate($dateTime);
        $excursion->setEndDate($dateTime);

        $excursionForm = $this->createForm(ExcursionType::class, $excursion);
        $excursionForm->handleRequest($request);

        //Récupère la ville organisatrice
        $siteRepository=$this->getDoctrine()->getRepository(Site::class);
        $numid= $this->getUser()->getSite();
        $site_id= $siteRepository->find($numid);
        $site=$site_id->getName();

        //Formulaire ville City
        $city = new City();
        $cityForm = $this->createForm(CityType::class, $city);
        $cityForm ->handleRequest($request);

        //Récupère les villes de la table City
        $citiesRepository = $this->getDoctrine()->getRepository(City::class);
        $cities = $citiesRepository->findAll();

        $place = new Place();
        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);

        if ($request->isXmlHttpRequest())
        {
            dd("test");
        }


        /*if (isset($data['foo']) && $data['foo'] instanceof Collection) {
            $data['foo'] = $this->getDoctrine()->getRepository(Foo::class)->findBy([
                                                            'id' => $data['foo']->toArray(),
            ]);
        }*/

        if($excursionForm->isSubmitted() && $excursionForm->isValid())
        {
            $stateRepository = $this->getDoctrine()->getRepository(State::class);
            $stateInitial = $stateRepository->find(5);

            $excursion->setOrganizer($this->getUser());

            //Rajoute l'organisateur en tant que participant à l'excursion
            $this->getUser()->addExcursion($excursion);

            $excursion->setState($stateInitial);

            $em = $this->getDoctrine()->getManager();

            //hydrate les champs de la table excursion
            $em->persist($excursion);
            //hydrate les champs de la table ville
            $em->persist($city);

            $em->flush();

            return $this->redirectToRoute('index');
        }



        return $this->render("excursion/create.html.twig",[
            'excursionForm' => $excursionForm->createView(),
            'cityForm' => $cityForm->createView(),
            'placeForm' => $placeForm->createView(),
            'user'=>$user,
            'site'=>$site,
            'cities'=>$cities
        ]);
    }

    /**
     * @Route(
     *     "/sortir/sortie/inscription/{id}",
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

            if($excursion->getRegistrationNumberMax() == $excursion->getRegisterExcursion()->count())
            {

                $stateRepository = $this->getDoctrine()->getRepository(State::class);
                $state= $stateRepository->find(4);

                $excursion->setState($state);
                $em->persist($excursion);
                $em->flush();
            }
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route(
     *     "/sortir/sortie/désinscription/{id}",
     *     name="excursion_unregister",
     *     requirements={"id":"\d+"}
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
            $em = $this->getDoctrine()->getManager();

            if($excursion->getRegistrationNumberMax() == $excursion->getRegisterExcursion()->count())
            {

                $stateRepository = $this->getDoctrine()->getRepository(State::class);
                $state= $stateRepository->find(3);

                $excursion->setState($state);
                $em->persist($excursion);
                $em->flush();
            }

            $user->removeExcursion($excursion);

            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route(
     *     "/sortir/sortie/modifier/{id}",
     *     name="excursion_modif",
     *     requirements={"id":"\d+"}
     *     )
     */
    public function modifExcursion(Request $request, $id)
    {

        $city = new City();

        //Récupère de la base les informations de la sortie grâce son id

        $excursionRepository = $this->getDoctrine()->getRepository(Excursion::class);
        $excursion = $excursionRepository->find($id);
        $previousState = $excursion->getState();


        //Créé le formulaire d'excursion
        $excursionForm = $this->createForm(ExcursionType::class, $excursion);
        $excursionForm->handleRequest($request);

        //Récupère les villes existants en BDD
        $cityRepository=$this->getDoctrine()->getRepository(City::class);
        $cities= $cityRepository->findAll();

        //Récupère la ville qui correspond à la sortie
        $city = $excursionRepository->findCity($id);


        if($excursionForm->isSubmitted() && $excursionForm->isValid())
        {
            $excursion->setOrganizer($this->getUser());
            $excursion->setState($previousState);

            $em = $this->getDoctrine()->getManager();
            $em->persist($excursion);

            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render("excursion/modif.html.twig",[
            'excursionForm' => $excursionForm->createView(),

            'excursion' => $excursion,
            'cities'=>$cities,
            'city'=>$city

        ]);
    }

    /**
     * @Route(
     *     "/sortir/sortie/publication/{id}",
     *     name="excursion_publish",
     *     requirements={"id":"\d+"}
     *     )
     */
    public function publishExcursion($id)
    {
        $excursionRepository = $this->getDoctrine()->getRepository(Excursion::class);
        $stateRepository = $this->getDoctrine()->getRepository(State::class);

        $excursion = $excursionRepository->find($id);
        $stateOpen = $stateRepository->find(3);

        $excursion->setState($stateOpen);

        $em = $this->getDoctrine()->getManager();
        $em->persist($excursion);
        $em->flush();

        return $this->redirectToRoute("index");
    }

    /**
     * @Route(
     *     "/sortir/sortie/annulation/{id}",
     *     name="excursion_delete",
     *     requirements={"id":"\d+"}
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
     * @Route(
     *      "/sortir/sortie/afficher/{id}",
     *      name="excursion_details",
     *      requirements={"id":"\d+"},
     *      methods={"GET","POST"})
     */
    public function excursionDetails($id, Request $request){
        //récupère les détails de l'excursion grâce à l'id
        $excursionRepository= $this->getDoctrine()->getRepository(Excursion::class);
        $excursion= $excursionRepository->find($id);

        //récupère le lieu Place dans la base
        $excursionRepository=$this->getDoctrine()->getRepository(Excursion::class);
        $place= $excursionRepository->findPlace($id);

        //récupère la ville dans la base
        $excursionRepository= $this->getDoctrine()->getRepository(Excursion::class);
        $city= $excursionRepository->findCity($id);

        //récupère les participants de l'excursion
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $participants = $userRepository->findParticipants($id);

        //récupère la ville organisatrice de l'organisateur
        $siteRepository= $this->getDoctrine()->getRepository(Site::class);
        $id = $this->getUser()->getSite();
        $site_id= $siteRepository->find($id);
        $site= $site_id->getName();

        //dd($participants);

        dd($participants);

        return $this->render("excursion/details.html.twig",[
            'excursion' => $excursion,
            'site'=>$site,
            'place'=>$place,
            'city'=>$city,
            'participants'=>$participants,
        ]);
    }

    /**
     * @Route(
     *     "sortir/sortie/annuler/{id}",
     *     name="excursion_cancel",
     *     requirements={"id": "\d+"},
     *     methods={"GET","POST"}
     *     )
     */
    public function cancelExcursion($id)
    {
        $excursionRepository = $this->getDoctrine()->getRepository(Excursion::class);
        $excursion = $excursionRepository->find($id);

        if($_POST)
        {
            dd($id);
            $stateRepository = $this->getDoctrine()->getRepository(State::class);
            $stateClose = $stateRepository->find(6);

            $excursion->setState($stateClose);
            $excursion->setCancelMessage($_POST["description"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($excursion);
            $em->flush();

            return $this->redirectToRoute("index");
        }

        return $this->render("excursion/cancel.html.twig",[
            "excursion" => $excursion
        ]);
    }
}