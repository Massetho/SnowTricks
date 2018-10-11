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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends Controller
{
    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Mailer $mailer
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request,
                             UserRepository $userRepository,
                             UserPasswordEncoderInterface $passwordEncoder,
                             Mailer $mailer
    )
    {
        // 1) build the form
        $token = new Token();
        $user = new User();
        $user->addToken($token);
        $form = $this->createForm(UserType::class, $user,  array('validation_groups' => array('registration')));

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Check if Mail is already used
            $userMail = $userRepository->loadUserByUsername($form->get('email')->getData());
            if($userMail)
                $this->addFlash('error', 'Email is already used.');

            //Check if Username is already used
            $userName = $userRepository->loadUserByUsername($form->get('username')->getData());
            if($userName)
                $this->addFlash('error', 'Username is already used.');

            if (!$userMail && !$userName) {

                // 3) Encode the password
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password)->setDateCreated(new \DateTime());

                // 4) save the User!
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // send them an email
                $content = $this->renderView(
                    'emails/registration.html.twig',
                    array('name' => $user->getUsername(), 'id' => $user->getId(), 'token' => $token->getToken())
                );
                $subject = "Confirm your email address";
                //SEND MAIL with Token
                $mailer->sendMail($subject, $content, $user->getUsername(), $user->getEmail());

                $this->addFlash('success', 'Confirmation email has been sent. Thank you !');
            }
        }

        return $this->render(
            'admin/register.html.twig',
            array('form' => $form->createView())
        );
    }
}
