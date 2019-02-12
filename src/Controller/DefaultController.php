<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/sortir/accueil", name="index", methods={"GET","POST"})
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/", name="home", methods={"GET","POST"})
     */
    public function home()
    {
        return $this->render('default/accueil.html.twig');
    }
}
