<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route(
     *     "sortir/utilisateur/gestion-profil",
     *      name="gestion_profil",
     *      methods={"GET","POST"}
     *     )
     */
    public function manage(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

            $user = $this->getUser();
            //dd($user);

            //Création du formulaire
            $userForm = $this->createForm(UserType::class, $user);

            //enregistre le formulaire dans la BDD
            $userForm->handleRequest($request);
            //dd($request);

            if ($userForm->isSubmitted() && $userForm->isValid()) {

                    //dd($userPassword);
                    //dd($userConfirmation);


                    //Encode le password tapé par l'utilisateur
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $userForm->get('password')->getData()
                        )
                    );

                    //Retourne l'entity manager:
                    $em = $this->getDoctrine()->getManager();

                    //On demande à Doctrine de sauvegarder notre instance :
                    $em->persist($user);

                    //pour exécuter :
                    $em->flush();

                    //Créer un message flash à afficher sur la prochaine page
                    $this->addFlash('success', "Vous avez modifié votre profil avec succès");
                    //dd($user);
                    //redirige vers la page de login
                    return $this->redirectToRoute('index');
                    }
                    else {
                        $this->addFlash('error', "La modification n'a pu se faire");
                    }

        //Affiche le formulaire et les infos venant de la BDD
        return $this->render('user/gestion_profil.html.twig', [
            "userForm" => $userForm->createView()
        ]);
    }


}
