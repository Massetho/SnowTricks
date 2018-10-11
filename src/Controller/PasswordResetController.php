<?php
/**
 * @description : Managing Login and password reset.
 * @Author : Quentin Thomasset
 */
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\TokenRepository;
use App\Form\UserPasswordType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordResetController extends Controller
{
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
            $this->addFlash('error', 'Invalid token.');
        } else {
            /*
             * Checking for token expiration date.
             */
            if (!$token->isValidToken()) {
                $this->addFlash('error', 'Token has expired. Please try again.');
            } else {

                $username = $user->getUsername();
                $email = $user->getEmail();
                /*
                 * If token is valid, create reset password Form.
                 */
                $form = $this->createForm(UserType::class, $user, array(
                    'validation_groups' => array('password_reset')));

                // 2) handle the submit
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    $user->setEmail($email);
                    $user->setUsername($username);

                    // 3) Encode the password
                    $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);

                    // 4) Remove Token
                    $user->removeToken($token);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($token);

                    // 5) save the User!
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
        return $this->redirectToRoute('trick_index');
    }
}
