<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Service\ImagePath;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{
    /**
     * @var Trick $trick
     */
    private $trick;

    /**
     * TrickController constructor.
     * @param Trick $trick     *
     */
    public function __construct(Trick $trick)
    {
        $this->trick = $trick;
    }

    /**
     * @Route("/", name="trick_index", methods="GET")
     * @param TrickRepository $trickRepository
     * @param ImagePath $homeImage
     * @return Response
     */
    public function index(TrickRepository $trickRepository,
                          ImagePath $homeImage): Response
    {

        return $this->render('trick/index.html.twig',
            ['tricks' => $trickRepository->findAll(),
                'homeImage' => $homeImage->getHomeImage()]);
    }

    /**
     * @Route("/trick/new", name="trick_new", methods="GET|POST")
     */
    public function newTrick(Request $request): Response
    {
        //$trick = $this->trick;
        $trick = new Trick();
        //$image = new Image();
        //$trick->addImage($image);

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Trick $trick
     * @param Request $request
     * @Route("/trick/{id}", name="trick_show", methods="GET")
     * @return Response
     */
    public function show(Trick $trick,
                         Request $request): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trick->addComment($comment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();
        }

        return $this->render('trick/show.html.twig', ['trick' => $trick,
            'commentForm' => $form->createView()]);
    }

    /**
     * @Route("/trick/{id}/edit", name="trick_edit", methods="GET|POST")
     */
    public function edit(Request $request, Trick $trick): Response
    {

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trick_edit', ['id' => $trick->getId()]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trick/{id}", name="trick_delete", methods="DELETE")
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trick);
            $em->flush();
        }

        return $this->redirectToRoute('trick_index');
    }
}
