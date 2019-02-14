<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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


            if ($userForm->isSubmitted() && $userForm->isValid()) {

                    //Encode le password tapé par l'utilisateur
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $userForm->get('password')->getData()
                        )
                    );

                    //$file enregistre le fichier uploadé de la photo
                    /**@var Symfony\Component\HttpFoundation\File\UploadedFile $file*/
                    $file = $user->getPhotoFile();
                    $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

                    // Déplace le fichier dans le répertoire
                    try {
                        $file->move(
                            $this->getParameter('photos_profil'),
                            $fileName
                        );
                    } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    }

                    // met à jour la propriété photoFile de User pour enregistrer le nom du fichier
                    $user->setPhotoFile($fileName);


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

    /**
     * @Route(
     *     "sortir/utilisateur/profil/{id}",
     *     name="user_detail",
     *     requirements={"id":"\d+"},
     *     methods={"GET"}
     * )
     */
    public function detailUser($id)
    {
           $userRepository = $this->getDoctrine()->getRepository(User::class);
           $user = $userRepository->find($id);

           return $this->render("user/detail.html.twig",[
               "user" => $user
           ]);
    }
}
