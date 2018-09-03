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

class ImageListener extends AbstractEntityListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $this->setDateCreated($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $this->setDateCreated($entity);
    }
}
