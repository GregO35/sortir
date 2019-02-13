<?php

namespace App\Controller;

use App\Entity\Excursion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home", methods={"GET","POST"})
     */
    public function home()
    {
        return $this->render('default/accueil.html.twig');
    }

}
