<?php
/**
 * @description : Managing registration and mail confirmation.
 * @Author : Quentin Thomasset
 */

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Entity\Token;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function register(
        Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             Mailer $mailer
    ) {
        // 1) build the form
        $token = new Token();
        $user = new User();
        $user->addToken($token);
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // send them an email
            //Make message
            $url = $this->generateUrl(
                'mail_confirmation',
                array('id' => $user->getId(),
                    'token' => $token->getToken())
            );
            $content = '<p>You have been registered. Please follow this link to activate your account :</p> <br> <p>http://symfony.test' . $url . '</p>';

            $subject = "Confirm your email address";
            //SEND MAIL with Token
            $mailer->sendMail($subject, $content, $user->getUsername(), $user->getEmail());

            $this->addFlash('success', 'Confirmation email has been sent. Thank you !');
        }

        return $this->render(
            'admin/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @param User $user
     * @param string $token
     * @param TokenRepository $tokenRepository
     *
     * @Route("/mail_confirm/{id}/{token}",
     *     name="mail_confirmation",
     *     methods="GET",
     *     requirements={"id"="\d+"})
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return Response
     */
    public function confirmMail(
        User $user,
                                $token,
                                TokenRepository $tokenRepository
    ) {
        /*
         * Get Token matching user & code
         * If not found, throw exception
         */
        $token = $tokenRepository->getMailConfirmationToken($user, $token);
        if (!$token) {
            throw $this->createNotFoundException(
                'Invalid token'
            );
        } else {
            /*
             * Checking token expiration
             */
            if (!$token->isValidToken()) {
                $msg = 'Error : Token has expired. Please try again.';
            } else {
                /*
                 * If token is valid, add new User role.
                 */
                $user->addRole('ROLE_ADMIN');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $msg = 'Thanks for confirming your email address. You can now login with your account.';
            }
        }

        return $this->render(
            'admin/mail_confirm.html.twig',
            array('message' => $msg)
        );
    }
}
