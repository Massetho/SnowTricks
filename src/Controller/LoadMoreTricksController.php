<?php
/**
 * @description : Returning more tricks for Ajax request
 * @Author : Quentin Thomasset
 */
namespace App\Controller;


use App\Repository\TrickRepository;
use App\Service\LastItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoadMoreTricksController extends Controller
{
    /**
     * @param TrickRepository $trickRepository
     * @param Request $request
     * @param LastItem $lastItem
     * @Route("/load_more_tricks",
     *     name="load_more_tricks",
     *     methods="GET")
     * @return Response
     */
    public function loadMoreTricks(
        TrickRepository $trickRepository,
        Request $request,
        LastItem $lastItem
    ) {
        /*
         * Getting last item id from request
         * Get a new tricks collection from repository
         */
        $x = $request->get('last_id');
        $tricks = $trickRepository->getMoreItems($x);
        $lastId = 0;

        if (!empty($tricks)) {
            $lastId = $lastItem->getLastItemId($tricks);
        }

        return $this->render(
            'trick/tricks_thumbs.html.twig',
            array('tricks' => $tricks,
                'last_id' => $lastId)
        );
    }

}
