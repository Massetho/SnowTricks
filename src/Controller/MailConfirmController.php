<?php
/**
 * @description : Managing Mail confirmation.
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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailConfirmController extends Controller
{
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
            $this->addFlash('error', 'Invalid token.');
        } else {
            /*
             * Checking token expiration
             */
            if (!$token->isValidToken()) {
                $this->addFlash('error', 'Token has expired. Please try again.');
            } else {
                /*
                 * If token is valid, add new User role.
                 */
                $user->addRole('ROLE_ADMIN');

                // Remove Token
                $user->removeToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($token);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Thanks for confirming your email address. You can now login with your account.');
            }
        }

        return $this->render('admin/mail_confirm.html.twig');
    }
}
