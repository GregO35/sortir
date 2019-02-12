<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExcursionController extends AbstractController
{
    /**
     * @Route("/excursion", name="excursion")
     */
    public function index()
    {
        return $this->render('excursion/index.html.twig', [
            'controller_name' => 'ExcursionController',
        ]);
    }
}
