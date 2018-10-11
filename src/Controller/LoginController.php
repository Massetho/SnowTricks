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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
}
