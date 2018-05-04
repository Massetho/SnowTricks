<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 04/05/2018
 * @time: 09:25
 */

namespace App\EventListener;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TrickListener extends AbstractEntityListener
{

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Trick) {
            return;
        }

        $this->makeCollections($entity);

    }

    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if (!$entity instanceof Trick) {
            return;
        }

        $this->setDateCreated($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Trick) {
            return;
        }

        $this->setDateUpdated($entity);
    }

    /**
     * Making sure my trick entity has 3 images and videos.
     *
     * @param Trick $trick
     */
    public function makeCollections(Trick $trick)
    {
        $imageNbr = $trick->getImages()->count();
        $videoNbr = $trick->getVideos()->count();

        $nbrCreate = 3 - $imageNbr;
        for($i = 0 ; $i < $nbrCreate ; $i++) {
            $child = new Image(); // instantiate a new child entity
            $trick->addImage($child); // add this instance to the parent entity
        }

        $nbrCreate = 3 - $videoNbr;
        for($i = 0 ; $i < $nbrCreate ; $i++) {
            $child = new Video(); // instantiate a new child entity
            $trick->addVideo($child); // add this instance to the parent entity
        }
    }

}