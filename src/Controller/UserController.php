<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


class UserController extends AbstractController
{
    /**
     * @Route(
     *     "/utilisateur/gestion-profil",
     *      name="gestion_profil",
     *      methods={"GET","POST"}
     *     )
     */
    public function manage(Request $request)
    {

        //$user = $this->get('security.token_storage')->getToken()->getUser();

            $user = new User();

            //Création du formulaire
            $userForm = $this->createForm(UserType::class, $user);

            //enregistre le formulaire dans la BDD
            $userForm->handleRequest($request);

            if ($userForm->isSubmitted() && $userForm->isValid()) {
                //Retourne l'entity manager:
                $em = $this->getDoctrine()->getManager();

                //On demande à Doctrine de sauvegarder notre instance :
                $em->persist($user);

                //pour exécuter :
                $em->flush();

                //Créer un message flash à afficher sur la prochaine page
                $this->addFlash('success', "Vous avez modifié votre profil avec succès");

            }
        //Affiche le formulaire et les infos venant de la BDD
        return $this->render('user/gestion_profil.html.twig', [
            "userForm" => $userForm->createView()]);
    }
}
