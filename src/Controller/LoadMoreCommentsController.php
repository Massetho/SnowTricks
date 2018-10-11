<?php
/**
 * @description : Returning more comments for Ajax request.
 * @Author : Quentin Thomasset
 */
namespace App\Controller;

use App\Entity\Trick;
use App\Repository\CommentRepository;
use App\Service\LastItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoadMoreCommentsController extends Controller
{
    /**
     * @Route("/load_more_comments",
     *     name="load_more_comments",
     *     methods="POST")
     * @param Request $request
     * @param LastItem $lastItem
     * @return Response
     */
    public function loadMoreComments(Request $request, LastItem $lastItem)
    {

        /*
         * Get trick_id and last comment'id from Request
         */
        $trickId = $request->get('trick_id');
        $commentId = $request->get('last_id');

        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->find($trickId);

        /*
         * If no trick is found, throw exception
         */
        if (!isset($trickId) || !isset($commentId) || !isset($trick)) {
            throw $this->createNotFoundException(
                'Invalid argument.'
            );
        }

        /*
         * Make a Criteria with the infos and recover next matching comments
         */
        $criteria = CommentRepository::commentsCriteria($trick, $commentId);
        $comments = $trick->getComments()->matching($criteria);
        $lastId = 0;

        if (!$comments->isEmpty()) {
            $lastId = $lastItem->getLastItemId($comments->toArray());
        }

        return $this->render(
            'trick/comments.html.twig',
            array('comments' => $comments,
                'last_id' => $lastId)
        );
    }
}
