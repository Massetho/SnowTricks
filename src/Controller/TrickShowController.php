<?php
/**
 * @description : Managing Home Page display.
 * @Author : Quentin Thomasset
 */
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\LastItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TrickShowController extends Controller
{
    /**
     * @param Trick $trick
     * @param Request $request
     * @param $modal
     * @Route("/trick/{id}/{modal}/{slug}",
     *     name="trick_show",
     *     methods={"GET","POST"},
     *     defaults={"modal": "0"},
     *     requirements={"id" :"\d+", "modal":"1|0"})
     * @return Response
     */
    public function show(Trick $trick,
                         $modal,
                         Request $request,
                         LastItem $lastItem
                        ): Response
    {
        //Creating and managing Comment form
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, array(
            'attr' => array(
                'id' => 'commentForm'
            )));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            $comment->setUser($this->getUser())->setDateCreated(new \DateTime());
            $trick->addComment($comment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();
        }

        //Construct criteria for comments display.
        $criteria = CommentRepository::commentsCriteria($trick);
        $comments = $trick->getComments()->matching($criteria);

        $lastId = false;
        if (!$comments->isEmpty()) {
            $lastId = $lastItem->getLastItemId($comments->toArray());
        }

        return $this->render('trick/show.html.twig', ['trick' => $trick,
            'modal' => $modal,
            'trick_id' => $trick->getId(),
            'last_id' => $lastId,
            'comments' => $comments,
            'commentForm' => $form->createView()]);
    }
}
