<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 01/05/2018
 * @time: 08:54
 */

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Image;

class ImageListener extends AbstractEntityListener
{

    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $this->setDateCreated($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $this->setDateCreated($entity);
    }

}