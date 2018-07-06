<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 23/05/2018
 * @time: 16:20
 */

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
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

class ImageController extends Controller
{
    /**
     * @param Request $request
     * @param Image $image
     *
     * @Route("/image/{id}",
     *     name="image_delete",
     *     methods="DELETE")
     *
     * @return Response
     */
    public function delete(Request $request,
                           Image $image): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
        }

        return $this->redirectToRoute('trick_show', ['id' => $image->getTrick()->getId()]);
    }


}