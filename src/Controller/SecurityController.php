<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\RegisterType;
use App\Form\SiteType;
use App\Util\Util;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route(
     *     "/register",
     *     name="app_register"
     * )
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        //Gère le formulaire de User
        $user = new User();
        $form = $this->createForm( RegisterType::class, $user);
        $form->handleRequest($request);

        //Récupère les sites existants en BDD
        $siteRepository=$this->getDoctrine()->getRepository(Site::class);
        $sites= $siteRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setAdministrator(false);
            $user->setActif(true);
            $user->setRoles(["user"]);
            $user->setPassword($encoder->encodePassword($user,$user->getPassword()));

            //cherche l'objet site qui porte le nom de $_POST('site')
            $siteRepository=$this->getDoctrine()->getRepository(Site::class);

            $id_user = $siteRepository->findOneBy(['name'=>$_POST{'site'}]);
            $user->setSite($id_user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            $entityManager->flush();

            return $this->redirectToRoute("app_login");
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
            'sites'=>$sites
        ]);
    }

    /**
     * @Route(
     *     "/login",
     *     name="app_login",
     *     methods={"GET","POST"}
     *     )
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     *@Route("/logout", name="app_logout")
     */
    public function logout(){}

    /**
     * @Route(
     *     "/mot-de-passe-oublié",
     *     name="app_recovery_password"
     *     )
     */
    public function forgetPassword(\Swift_Mailer $mailer)
    {
        if($_POST)
        {
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepository->findOneBy( [ "mail" => $_POST["mail"] ] );

            if($user != null)
            {
                $util = new Util();
                $resetPassword = $util->randomString();
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('send@example.com')
                    ->setTo('recipient@example.com')
                    ->setBody(
                        $this->renderView(
                        // templates/emails/registration.html.twig
                            'emails/forgetPassword.html.twig',[
                                'name' => $user->getUsername(),
                                'resetCode' => $resetPassword
                            ]),
                        'text/html'
                    );
                $mailer->send($message);

                $user->setResetPassword($resetPassword);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute("app_login");
            }

        }

        return $this->render("security/forgetPassword.html.twig");
    }

    /**
     * @Route(
     *     "/nouveau-mot-de-passe/{id}",
     *     name="app_new_password",
     *     requirements={"id":"[a-z0-9]+"}
     * )
     */
    public function newPassword($id, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findOneBy(["resetPassword" => $id]);

        if($user != null) {
            $form = $this->createForm(NewPasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                $user->setResetPassword(null);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }

            return $this->render("security/newPassword.html.twig", [
                "formPassword" => $form->createView()
            ]);
        }

        return $this->redirectToRoute("app_login");
    }
}