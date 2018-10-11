<?php
/**
 * @description : Managing Login and password reset.
 * @Author : Quentin Thomasset
 */
namespace App\Controller;

use App\Entity\Token;
use App\Form\MailType;
use App\Repository\UserRepository;
use App\Service\Mailer;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends Controller
{
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
        $form = $this->createForm(MailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('email')) {
                /*
                 * Checking if email exists in database and return corresponding user object.
                 */
                $user = $userRepository->loadUserByUsername($form->get('email')->getData());
                if ($user) {
                    $token = new Token();
                    $token->generate()->setDateCreated(new \DateTime());
                    $user->addToken($token);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    //Make message
                    $content = $this->renderView(
                        'emails/new_password.html.twig',
                        array('name' => $user->getUsername(), 'id' => $user->getId(), 'token' => $token->getToken())
                    );

                    //SEND MAIL with Token
                    $mailer->sendMail("Reset your password", $content, $user->getUsername(), $user->getEmail());
                    $this->addFlash('success', 'Email sent !');
                } else {
                    $this->addFlash('error', 'No user found for this email address.');
                }
            } else {
                $this->addFlash('error', 'Invalid address.');
            }
        }

        return $this->render('admin/forgot_password.html.twig', ['form' => $form->createView()]);
    }
}
