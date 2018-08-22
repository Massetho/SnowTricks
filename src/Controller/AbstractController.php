<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class AbstractController extends Controller
{

    public function checkLogin(SessionInterface $session)
    {
        if ($session->has('auth')) {

        }
        return false;
    }


    /**
     * @param array $tokens
     * @return $this
     */
    public function cleanTokens($tokens)
    {
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($tokens as $token) {
            if (!$token->isValidToken()) {
                $entityManager->remove($token);
            }
        }
        $entityManager->flush();
    }
}
