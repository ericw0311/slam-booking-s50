<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\User;
use App\Entity\UserContext;
use App\Form\UserModifyType;
use App\Form\UserPasswordType;

class UserController extends AbstractController
{
     // Affichage du detail de l' utilisateur connecté
     /**
     * @Route("/{_locale}/user/edit", name="user_edit")
     */
     public function edit()
     {
         $connectedUser = $this->getUser();
         $em = $this->getDoctrine()->getManager();
         $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
         return $this->render('user/edit.html.twig', array('userContext' => $userContext, 'user' => $connectedUser));
     }

     /**
      * @Route("/{_locale}/forgotloginorpassword", name="user_forgot_login_or_password")
      */
     public function forgot_login_or_password(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, TranslatorInterface $translator)
     {
         $email = new Email();
         $form = $this->createForm(SD_EmailType::class, $email);
         if ($request->isMethod('POST')) {
             $form->submit($request->request->get($form->getName()));
             if ($form->isSubmitted() && $form->isValid()) {
                 $em = $this->getDoctrine()->getManager();
                 $uRepository = $em->getRepository(User::class);
                 $user = $uRepository->findOneBy(array('email' => $email->getEmail()));
                 if ($user === null) {
                     $request->getSession()->getFlashBag()->add('notice', 'user.email.not.found');
                     return $this->redirectToRoute('user_forgot_login_or_password');
                 }
                 // On réinitialise le mot de passe
                 $decodePassword = 'slam'.$user->getID();
                 $encodePassword = $passwordEncoder->encodePassword($user, $decodePassword);
                 $user->setPassword($encodePassword);
                 $em->flush();
                 $message = (new \Swift_Message($translator->trans('user.login.and.password.sent.email.title')))
                 ->setFrom(['slam.booking.web@gmail.com' => 'Slam Booking'])
                 ->setTo($email->getEmail())
                 ->setBody(
                     $this->renderView(
                         'emails/login.and.password.sent.html.twig',
                         array('user' => $user, 'password' => $decodePassword)
                     ),
                     'text/html'
                 );
                 $mailer->send($message);
                 $request->getSession()->getFlashBag()->add('notice', 'user.login.and.password.sent');
                 return $this->redirectToRoute('app_login');
             }
         }
         return $this->render('user/forgot.login.or.password.html.twig', array('form' => $form->createView()));
     }

     // Modification de l' utilisateur connecté
     /**
     * @Route("/{_locale}/user/modify", name="user_modify")
     */
     public function modify(Request $request)
     {
         $connectedUser = $this->getUser();
         $em = $this->getDoctrine()->getManager();
         $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
         $form = $this->createForm(UserModifyType::class, $connectedUser);
         if ($request->isMethod('POST')) {
             $form->submit($request->request->get($form->getName()));
             if ($form->isSubmitted() && $form->isValid()) {
                 $em->flush();
                 $request->getSession()->getFlashBag()->add('notice', 'user.updated.ok');
                 return $this->redirectToRoute('user_edit');
             }
         }
         return $this->render('user/modify.html.twig', array('userContext' => $userContext, 'user' => $connectedUser, 'form' => $form->createView()));
     }

     // Modification du mot de passe de l' utilisateur connecté
     /**
     * @Route("/{_locale}/user/password", name="user_password")
     */
     public function password(Request $request, UserPasswordEncoderInterface $passwordEncoder)
     {
         $connectedUser = $this->getUser();
         $em = $this->getDoctrine()->getManager();
         $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
         $form = $this->createForm(UserPasswordType::class, $connectedUser);
         if ($request->isMethod('POST')) {
             $form->submit($request->request->get($form->getName()));
             if ($form->isSubmitted() && $form->isValid()) {
                 $password = $passwordEncoder->encodePassword($connectedUser, $connectedUser->getPassword());
                 $connectedUser->setPassword($password);
                 $em->flush();
                 $request->getSession()->getFlashBag()->add('notice', 'user.password.updated.ok');
                 return $this->redirectToRoute('user_edit');
             }
         }
         return $this->render('user/password.html.twig', array('userContext' => $userContext, 'user' => $connectedUser, 'form' => $form->createView()));
     }
}
