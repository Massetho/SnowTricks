<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\EditTrickType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Service\ImagePath;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class TrickController extends Controller
{
    /**
     * @var Trick $trick
     */
    private $trick;

    /**
     * TrickController constructor.
     * @param Trick $trick
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
        $tricks = $trickRepository->getLastItems();
        $lastId = false;
        if (!empty($tricks))
            $lastId = $this->getLastItemId($tricks);

        return $this->render('trick/index.html.twig',
            ['tricks' => $tricks,
                'last_id' => $lastId]);
    }

    /**
     * @param Request $request
     * @param $modal
     * @Route("/trick/new/{modal}",
     *     name="trick_new",
     *     methods="GET|POST",
     *     defaults={"modal": "0"},
     *     requirements={"modal":"1|0"})
     * @return Response
     */
    public function newTrick(Request $request, $modal): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick, array(
            'attr' => array(
                'id' => 'trickFormEdit'
            )));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'modal' => $modal,
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Trick $trick
     * @param Request $request
     * @param $modal
     * @Route("/trick/{id}/{modal}",
     *     name="trick_show",
     *     methods={"GET","POST"},
     *     defaults={"modal": "0"},
     *     requirements={"id" :"\d+", "modal":"1|0"})
     * @return Response
     */
    public function show(Trick $trick,
                         $modal,
                         Request $request): Response
    {

        $topImage = $trick->getTopImage();
        $images = $trick->getBottomImages();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, array(
            'attr' => array(
                'id' => 'commentForm'
            )));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            $user = $this->getUser();
            $comment->setUser($user);
            $trick->addComment($comment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();
        }

        $criteria = CommentRepository::commentsCriteria($trick);
        $comments = $trick->getComments()->matching($criteria);

        $lastId = false;
        if (!$comments->isEmpty())
            $lastId = $this->getLastItemId($comments->toArray());

        return $this->render('trick/show.html.twig', ['trick' => $trick,
            'modal' => $modal,
            'top_image' => $topImage,
            'images' => $images,
            'trick_id' => $trick->getId(),
            'last_id' => $lastId,
            'comments' => $comments,
            'commentForm' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param Trick $trick
     * @param $modal
     * @Route("/admin/trick/edit/{id}/{modal}",
     *     name="trick_edit",
     *     methods="GET|POST",
     *     defaults={"modal": "0"},
     *     requirements={"id" :"\d+", "modal":"1|0"})
     * @return Response
     */
    public function edit(Request $request, Trick $trick, $modal): Response
    {
        $images = $trick->getImages()->toArray();
        if (is_array($images))
            array_shift($images);

        $form = $this->createForm(EditTrickType::class, $trick, array(
            'attr' => array(
                'id' => 'trickFormEdit'
            )));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('bottomImages')) {
                foreach ($form->get('bottomImages') as $formBI) {
                    $image = $formBI->getData();
                    $trick->addBottomImages($image);
                }
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Trick has been updated.');

        }

        return $this->render( 'trick/edit.html.twig', [
            'modal' => $modal,
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @pâram Trick $trick
     * @Route("/trick/{id}",
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
        }

        return $this->redirectToRoute('trick_index');
    }

    /**
     * @param TrickRepository $trickRepository
     * @param Request $request
     * @Route("/load_more_tricks",
     *     name="load_more_tricks",
     *     methods="GET")
     * @return Response
     */
    public function loadMoreTricks(TrickRepository $trickRepository,
                                   Request $request)
    {
        $x = $request->get('last_id');
        $tricks = $trickRepository->getMoreItems($x);
        $lastId = $this->getLastItemId($tricks);

        return $this->render('trick/tricks_thumbs.html.twig',
            array('tricks' => $tricks,
                'last_id' => $lastId));
    }

    /**
     * @param array $array
     * @return int
     */
    public function getLastItemId(array $array)
    {
        return array_values(array_slice($array, -1))[0]->getId();
    }

    /**
     * @Route("/load_more_comments",
     *     name="load_more_comments",
     *     methods="POST")
     * @param Request $request
     * @return Response
     */
    public function loadMoreComments(Request $request)
    {

        $trickId = $request->get('trick_id');
        $commentId = $request->get('last_id');

        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->find($trickId);


        if (!isset($trickId) || !isset($commentId) || !isset($trick)) {
            throw $this->createNotFoundException(
                'Invalid argument.'
            );
        }

        $criteria = CommentRepository::commentsCriteria($trick, $commentId);
        $comments = $trick->getComments()->matching($criteria);
        $lastId = 0;

        if (!$comments->isEmpty())
            $lastId = $this->getLastItemId($comments->toArray());

        return $this->render('trick/comments.html.twig',
            array('comments' => $comments,
                'last_id' => $lastId));
    }
}
