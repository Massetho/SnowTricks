<?php
/**
 * @description : Managing Login and password reset.
 * @Author : Quentin Thomasset
 */
namespace App\Controller;

use App\Entity\User;
use App\Entity\Token;
use App\Form\MailType;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use App\Form\UserPasswordType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends Controller
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     *
     * @Route("/login", name="login")
     *
     * @return Response
     */
    public function LoginAction(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @param UserRepository $userRepository
     * @param Request $request
     * @param Mailer $mailer
     *
     * @Route("/forgot_password",
     *     name="forgot_password",
     *     methods="GET|POST")
     *
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function forgotPassword(
        UserRepository $userRepository,
                             Request $request,
                             Mailer $mailer
    ) {
        $message = '';

        $form = $this->createForm(MailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('email')) {
                /*
                 * Checking if email exists in database and return corresponding user object.
                 */
                $email = $form->get('email')->getData();
                $user = $userRepository->loadUserByUsername($email);
                if ($user) {
                    $token = new Token();
                    $user->addToken($token);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    //Make message
                    $url = $this->generateUrl(
                        'password_reset',
                        array('id' => $user->getId(),
                            'token' => $token->getToken())
                    );
                    $content = '<p>You asked for a password reset. If you want to change your password, please follow this link :</p> <br> <p>http://symfony.test' . $url . '</p>';
                    $subject = "Reset your password";

                    /*
                     * SEND MAIL with Token
                     */
                    $mailer->sendMail($subject, $content, $user->getUsername(), $user->getEmail());
                    $message = 'Email sent !';
                } else {
                    $message = 'No user found for this email address';
                }
            } else {
                $message = 'Invalid address';
            }
        }

        return $this->render('admin/forgot_password.html.twig', [
            'message' => $message,
            'form' => $form->createView()]);
    }

    /**
     * @param User $user
     * @param string $token
     * @param TokenRepository $tokenRepository
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @Route("/password_reset/{id}/{token}",
     *     name="password_reset",
     *     methods="GET|POST",
     *     requirements={"id"="\d+"})
     *
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function passwordReset(
        User $user,
                                $token,
                                TokenRepository $tokenRepository,
                                Request $request,
                                UserPasswordEncoderInterface $passwordEncoder
    ) {

        /*
         * Looking for a token object matching token code and user id.
         */
        $token = $tokenRepository->getMailConfirmationToken($user, $token);
        if (!$token) {
            throw $this->createNotFoundException(
                'Invalid token'
            );
        } else {
            /*
             * Checking for token expiration date.
             */
            if (!$token->isValidToken()) {
                throw $this->createNotFoundException(
                    'Token has expired. Please try again.'
                );
            } else {
                /*
                 * If token is valid, create reset password Form.
                 */
                $form = $this->createForm(UserPasswordType::class, $user);

                // 2) handle the submit
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    // 3) Encode the password
                    $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);

                    // 4) Remove Token
                    $user->removeToken($token);

                    // 5) save the User!
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Password has been successfully changed.');

                    return $this->redirectToRoute('trick_index');
                }

                return $this->render(
                    'admin/new_password.html.twig',
                    array('form' => $form->createView())
                );
            }
        }
    }
}
