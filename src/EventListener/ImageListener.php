<?php
/**
 * @description : Image Listener
 * @Author : Quentin Thomasset
 */

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Image;
use App\Entity\TopImage;
use App\Service\ImagePath;

class ImageListener
{

    /**
     * @var ImagePath
     */
    private $pathMaker;

    /**
     * ImageUploadListener constructor.
     * @param ImagePath $pathMaker
     */
    public function __construct(ImagePath $pathMaker)
    {
        $this->pathMaker = $pathMaker;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $this->pathMaker->setPath($entity);
    }

}
