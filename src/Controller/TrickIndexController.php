<?php
/**
 * @description : Managing Home Page display.
 * @Author : Quentin Thomasset
 */
namespace App\Controller;

use App\Repository\TrickRepository;
use App\Service\LastItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TrickIndexController extends Controller
{
    /**
     * @Route("/", name="trick_index", methods="GET")
     * @param TrickRepository $trickRepository
     * @param LastItem $lastItem
     * @return Response
     */
    public function index(TrickRepository $trickRepository, LastItem $lastItem): Response
    {
        /*
         * Get last registered tricks to show on front page.
         * Sending last Id var for load more ajax request.
         */
        $tricks = $trickRepository->getLastItems();
        $lastId = false;
        if (!empty($tricks)) {
            $lastId = $lastItem->getLastItemId($tricks);
        }

        return $this->render(
            'trick/index.html.twig',
            ['tricks' => $tricks,
                'last_id' => $lastId]
        );
    }
}
