<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\RegisterType;
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
        $user = new User();
        $form = $this->createForm( RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setAdministrator(false);
            $user->setActif(false);
            $user->setRoles(["user"]);
            $user->setPassword($encoder->encodePassword($user,$user->getPassword()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("app_login");
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView()
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
     *     requirements={"id":"\d+"}
     * )
     */
    public function newPassword(Request $request)
    {
        $user =new User();
        $form = $this->createForm( NewPasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

        }

        return $this->render("security/newPassword.html.twig",[
           "formPassword" => $form->createView()
        ]);
    }
}