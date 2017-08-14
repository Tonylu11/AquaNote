<?php
  namespace AppBundle\Controller;

  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Component\HttpFoundation\Request;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
  use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
  use AppBundle\Entity\User;
  use AppBundle\Form\UserType;


  class SecurityController extends Controller
  {

    /**
     * @Route("/login", name="login")
     */
     public function loginAction(Request $request)
     {
        $authUtils = $this->get('security.authentication_utils');

        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
     }
    /**
     * @Route("/register", name="register")
     */
     public function registerAction(Request $request)
     {
       $em = $this->getDoctrine()->getManager();
       $user = new User();
       $form = $this->createForm(UserType::class, $user);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
         $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
         $user->setPassword($password);
         $em->persist($user);
         $em->flush();
         return $this->redirectToRoute('login');
       }
       return $this->render('security/register.html.twig', array(
         'form' => $form->createView()
       ));
     }
     /**
      * @Route("/logout", name="logout")
      */
      public function logoutAction(Request $request)
      {
      // UNREACHABLE CODE
      }
  }
?>
