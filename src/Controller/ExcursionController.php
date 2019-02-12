<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
