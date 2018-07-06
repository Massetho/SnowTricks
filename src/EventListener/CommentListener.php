<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 29/05/2018
 * @time: 16:55
 */

namespace App\EventListener;

use App\Entity\Comment;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class CommentListener extends AbstractEntityListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if (!$entity instanceof Comment) {
            return;
        }

        $this->setDateCreated($entity);
    }

}