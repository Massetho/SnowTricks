<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 04/05/2018
 * @time: 10:24
 */

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Video;

class VideoListener extends AbstractEntityListener
{

    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if (!$entity instanceof Video) {
            return;
        }

        $this->setDateCreated($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Video) {
            return;
        }

        $this->setDateCreated($entity);
    }

}