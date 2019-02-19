<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Entity\State;
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
                    /**@var Symfony\Component\HttpFoundation\File\UploadedFile $file */

                    $file = $userForm->get('photo_file')->getData();



                    if($file !== null) {

                        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

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
                    }

                    //Retourne l'entity manager:
                    $em = $this->getDoctrine()->getManager();

                    //On demande à Doctrine de sauvegarder notre instance :
                    $em->persist($user);

                    //pour exécuter :
                    $em->flush();

                    //Créer un message flash à afficher sur la prochaine page
                    $this->addFlash('success', "Vous avez modifié votre profil avec succès");

                    //redirige vers la page de login
                    return $this->redirectToRoute('index');
                    }
                    else {
                        $this->addFlash('error', "La modification n'a pu se faire");
                    }

        //Affiche le formulaire et les infos venant de la BDD
        return $this->render('user/gestion_profil.html.twig', [
            "userForm" => $userForm->createView(),
            "user"=>$user,
         ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
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

    /**
     * @Route(
     *     "sortir/admin/liste-utilisateur",
     *     name="user_list"
     * )
     */
    public function displayUsers()
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();

        return $this->render("user/list.html.twig",[
           "users" => $users
        ]);
    }

    /**
     * @Route(
     *     "sortir/admin/désactiver/{id}",
     *     name="user_desactivate",
     *     requirements={"id":"\d+"}
     * )
     */
    public function desactivate($id)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($id);
        $user->setActif(false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("user_list");
    }

    /**
     * @Route(
     *     "sortir/admin/activer/{id}",
     *     name="user_activate",
     *     requirements={"id":"\d+"}
     * )
     */
    public function activate($id)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($id);
        $user->setActif(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("user_list");
    }

    /**
     * @Route(
     *     "sortir/admin/supprimer/{id}",
     *     name="user_erase",
     *     requirements={"id":"\d+"}
     * )
     */
    public function eraseUser($id)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $excursionRepository = $this->getDoctrine()->getRepository(Excursion::class);
        $em = $this->getDoctrine()->getManager();

        $user = $userRepository->find($id);
        $registered = $excursionRepository->findByRegistered($user);
        $organized = $excursionRepository->findBy(["organizer"=>$user->getId()]);

        foreach ($organized as $excursion) {
            $excursionRepository->remove($excursion->getId());
        }

        foreach ($registered as $excursion)
        {
            $excursion->removeRegisterExcursion($user);
            $em->persist($excursion);
        }
        $em->flush();

        $userRepository->remove($user->getId());

        return $this->redirectToRoute("user_list");
    }
}
