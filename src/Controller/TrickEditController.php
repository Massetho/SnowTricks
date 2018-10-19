<?php
/**
 * @description : Managing Trick edition.
 * @Author : Quentin Thomasset
 */
namespace App\Controller;

use App\Entity\Trick;
use App\Form\EditTrickType;
use App\Form\TrickType;
use App\Service\ImageUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TrickEditController extends Controller
{
    /**
     * @param Request $request
     * @param Trick $trick
     * @param $modal
     * @param ImageUploader $uploader
     * @Route("/admin/trick/edit/{id}/{modal}",
     *     name="trick_edit",
     *     methods="GET|POST",
     *     defaults={"modal": "0"},
     *     requirements={"id" :"\d+", "modal":"1|0"})
     * @return Response
     */
    public function edit(Request $request, Trick $trick, $modal, ImageUploader $uploader): Response
    {

        //Create Edit Trick form.
        $form = $this->createForm(EditTrickType::class, $trick, array(
            'attr' => array(
                'id' => 'trickFormEdit'
            )));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploader->handleForm($form, $trick);
            $trick->setDateUpdated(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Trick has been updated.');

            //Adapting display for small or big screen
            if ($modal == 1) {
                return new Response();
            } else {
                return $this->redirectToRoute('trick_index');
            }
        }

        return $this->render('trick/edit.html.twig', [
            'modal' => $modal,
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}
