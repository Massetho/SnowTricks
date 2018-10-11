<?php
/**
 * @description : Managing Trick removal.
 * @Author : Quentin Thomasset
 */
namespace App\Controller;

use App\Entity\Trick;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TrickDeleteController extends Controller
{
    /**
     * @param Request $request
     * @param Trick $trick
     * @Route("/admin/trick/{id}",
     *     name="trick_delete",
     *     methods="DELETE")
     * @return Response
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trick);
            $em->flush();
            $this->addFlash('success', 'Trick has been successfully deleted.');
        }

        return $this->redirectToRoute('trick_index');
    }
}
