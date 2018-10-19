<?php
/**
 * @description : Managing Trick creation.
 * @Author : Quentin Thomasset
 */
namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Service\ImageUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TrickNewController extends Controller
{
    /**
     * @param Request $request
     * @param $modal
     * @param ImageUploader $uploader
     * @Route("/admin/new/{modal}",
     *     name="trick_new",
     *     methods="GET|POST",
     *     defaults={"modal": "0"},
     *     requirements={"modal":"1|0"})
     * @return Response
     */
    public function newTrick(Request $request, $modal, ImageUploader $uploader): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick, array(
            'attr' => array(
                'id' => 'trickFormEdit'
            )));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploader->handleForm($form, $trick);
            $trick->setDateCreated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();
            $this->addFlash('success', 'Trick has been created successfully.');

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'modal' => $modal,
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}
